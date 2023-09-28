function submit_form() {
  $(document).ready(function () {
    document.getElementById("submit").addEventListener("click", function (event) {
      event.preventDefault(); // prevent reload or redirect

    $.ajax({
      dataType: 'JSON',
      url: 'contact.php',
      type: 'POST',
      data: $('#email-form').serialize() + '&datetime=' + new Date(), // convert data into format like "name=John&email=john%40example.com"
      beforeSend: function (xhr) {
        $('#submit').html('SENDING...'); // text button will change to sending before submit the form
      },
      success: function (response) {
        if (response) {
          console.log('Response from server:', response);
          console.log('Data sent in AJAX request:', $('#email-form').serialize() + '&datetime=' + new Date());

          if (response['isSuccess']) {
            $('#alert-message').html('<div class="alert alert-success">' + response['msg'] + '</div>');
            $('input, textarea').val(function () {
              return this.defaultValue;
            });
          }
          else {
            $('#alert-message').html('<div class="alert alert-danger">' + response['msg'] + '</div>');
          }
        }
      },
      error: function () {
        $('#alert-message').html('<div class="alert alert-danger">Errors occur. Please try again later.</div>');
      },
      complete: function () {
        $('#submit').html('SEND MESSAGE');
      }
    });
  });
});
}