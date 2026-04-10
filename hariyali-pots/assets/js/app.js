$(function () {
  const searchInput = $('#liveSearch');
  if (searchInput.length) {
    searchInput.on('keyup', function () {
      const query = $(this).val().trim();
      if (query.length < 2) return $('#searchResults').hide();
      $.get('/hariyali-pots/ajax/search.php', { q: query }, function (html) {
        $('#searchResults').html(html).show();
      });
    });
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.search-box').length) $('#searchResults').hide();
    });
  }

  $('.ajax-cart').on('click', function (e) {
    e.preventDefault();
    $.post('/hariyali-pots/ajax/cart.php', { product_id: $(this).data('id'), qty: 1 }, function (res) {
      showToast(res.message || 'Added to cart');
    }, 'json');
  });

  $('.ajax-wishlist').on('click', function (e) {
    e.preventDefault();
    $.post('/hariyali-pots/ajax/wishlist.php', { product_id: $(this).data('id') }, function (res) {
      showToast(res.message || 'Wishlist updated');
    }, 'json');
  });
});

function showToast(message) {
  const toast = $(`<div class='toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3' role='alert'><div class='d-flex'><div class='toast-body'>${message}</div><button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast'></button></div></div>`);
  $('body').append(toast);
  new bootstrap.Toast(toast[0]).show();
  setTimeout(() => toast.remove(), 4000);
}
