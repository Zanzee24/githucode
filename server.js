const path = require("path");
const fs = require("fs");
const express = require("express");
const session = require("express-session");
const multer = require("multer");
const bcrypt = require("bcryptjs");
const sqlite3 = require("sqlite3").verbose();

const app = express();
const PORT = process.env.PORT || 3000;
const UPLOAD_DIR = path.join(__dirname, "uploads");
const DB_PATH = path.join(__dirname, "data", "files.db");

if (!fs.existsSync(UPLOAD_DIR)) {
  fs.mkdirSync(UPLOAD_DIR, { recursive: true });
}
if (!fs.existsSync(path.dirname(DB_PATH))) {
  fs.mkdirSync(path.dirname(DB_PATH), { recursive: true });
}

const db = new sqlite3.Database(DB_PATH);

db.serialize(() => {
  db.run(
    `CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL
    )`
  );
  db.run(
    `CREATE TABLE IF NOT EXISTS files (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      original_name TEXT NOT NULL,
      stored_name TEXT NOT NULL,
      size INTEGER NOT NULL,
      uploaded_at TEXT NOT NULL,
      uploaded_by TEXT NOT NULL
    )`
  );

  db.get("SELECT COUNT(*) AS count FROM users", (err, row) => {
    if (err) {
      console.error("Failed to count users:", err);
      return;
    }
    if (row.count === 0) {
      const hash = bcrypt.hashSync("password123", 10);
      db.run(
        "INSERT INTO users (username, password_hash) VALUES (?, ?)",
        ["admin", hash]
      );
      console.log("Seeded default user: admin / password123");
    }
  });
});

app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(
  session({
    secret: "file-portal-secret",
    resave: false,
    saveUninitialized: false,
  })
);

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, UPLOAD_DIR);
  },
  filename: (req, file, cb) => {
    const safeName = `${Date.now()}-${file.originalname.replace(/\s+/g, "_")}`;
    cb(null, safeName);
  },
});

const upload = multer({ storage });

const requireAuth = (req, res, next) => {
  if (req.session?.user) {
    return next();
  }
  return res.redirect("/login");
};

app.use(express.static(path.join(__dirname, "public")));

app.get("/", (req, res) => {
  if (req.session?.user) {
    return res.redirect("/app");
  }
  return res.redirect("/login");
});

app.get("/login", (req, res) => {
  res.sendFile(path.join(__dirname, "public", "login.html"));
});

app.post("/login", (req, res) => {
  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).send("Missing credentials");
  }

  db.get(
    "SELECT username, password_hash FROM users WHERE username = ?",
    [username],
    (err, row) => {
      if (err) {
        console.error("Login error:", err);
        return res.status(500).send("Server error");
      }
      if (!row || !bcrypt.compareSync(password, row.password_hash)) {
        return res.status(401).send("Invalid username or password");
      }
      req.session.user = { username: row.username };
      return res.redirect("/app");
    }
  );
});

app.post("/logout", (req, res) => {
  req.session.destroy(() => {
    res.redirect("/login");
  });
});

app.get("/app", requireAuth, (req, res) => {
  res.sendFile(path.join(__dirname, "public", "app.html"));
});

app.get("/api/files", requireAuth, (req, res) => {
  db.all(
    "SELECT id, original_name, size, uploaded_at, uploaded_by FROM files ORDER BY uploaded_at DESC",
    (err, rows) => {
      if (err) {
        console.error("Failed to fetch files:", err);
        return res.status(500).json({ error: "Failed to fetch files" });
      }
      return res.json(rows);
    }
  );
});

app.post("/upload", requireAuth, upload.single("file"), (req, res) => {
  if (!req.file) {
    return res.status(400).send("No file uploaded");
  }
  const payload = {
    original_name: req.file.originalname,
    stored_name: req.file.filename,
    size: req.file.size,
    uploaded_at: new Date().toISOString(),
    uploaded_by: req.session.user.username,
  };
  db.run(
    `INSERT INTO files (original_name, stored_name, size, uploaded_at, uploaded_by)
     VALUES (?, ?, ?, ?, ?)` ,
    [
      payload.original_name,
      payload.stored_name,
      payload.size,
      payload.uploaded_at,
      payload.uploaded_by,
    ],
    (err) => {
      if (err) {
        console.error("Failed to record file:", err);
        return res.status(500).send("Failed to save file metadata");
      }
      return res.redirect("/app");
    }
  );
});

app.get("/download/:id", requireAuth, (req, res) => {
  db.get(
    "SELECT original_name, stored_name FROM files WHERE id = ?",
    [req.params.id],
    (err, row) => {
      if (err) {
        console.error("Failed to fetch file:", err);
        return res.status(500).send("Failed to fetch file");
      }
      if (!row) {
        return res.status(404).send("File not found");
      }
      const filePath = path.join(UPLOAD_DIR, row.stored_name);
      return res.download(filePath, row.original_name);
    }
  );
});

app.listen(PORT, () => {
  console.log(`File portal running on http://localhost:${PORT}`);
});
