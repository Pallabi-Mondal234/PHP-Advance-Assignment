$(document).ready(function () {
  $('#emailForm').on('submit', function (e) {
    e.preventDefault(); 

    var email = $('#email').val().trim();
    var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === '') {
      $('#message').html("Please enter your email.");
      return;
    }

    //Check the email pattern.
    if (!email_pattern.test(email)) {
      $('#message').html("Please enter a valid email address");
      return;
    }
    //Send Email using AJAX.
    $.ajax({
      url: 'result.php',
      method: 'POST',
      data: { email: email },
      beforeSend: function () {
        $('#message').html('Checking email...');
      },
      success: function (response) {
        const trimmed_response = response.trim();
        if (trimmed_response === 'success') {
          $("#emailForm")[0].reset();
          $('#message').removeClass('error').addClass('success').html("Email sent successfully.");
        }
        else {
          $('#message').removeClass('success').addClass('error').html(trimmed_response);
        }
      },
      error: function (xhr, status, error) {
        $('#message').html("AJAX error: " + error);
      }
    });
  });
});

