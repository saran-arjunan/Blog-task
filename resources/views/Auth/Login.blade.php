<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('Assets/Login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <div class="login-root">
        <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">

            <div class="box-root padding-top--24 flex-flex flex-direction--column"
                style="flex-grow: 1; z-index: 9;">
                <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
                    <h1><a href="#" rel="dofollow">Blog Admin Login</a></h1>
                </div>
                <div class="container">
                    <div class="formbg-outer">


                        <div class="formbg">
                            <div class="formbg-inner padding-horizontal--48">

                                <form id="stripe-login">
                                    <div class="field padding-bottom--24">
                                        <div class="text-center">
                                            <span id="message" class="fs-6 text-danger"></span>
                                        </div>
                                        <label for="user_name">User Name</label>
                                        <input type="text" name="user_name" id="user_name"
                                            placeholder="Enter the User Name">
                                        <div class="error-message" id="user-name-error"></div>
                                    </div>
                                    <div class="field padding-bottom--24">
                                        <div class="grid--50-50">
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="password-input-container">
                                            <input type="password" name="password" id="password" placeholder="Enter the Password">
                                            <span class="toggle-password">
                                                <i class="fas fa-eye" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <div class="error-message" id="password-error"></div>
                                    </div>

                                    <div class="field padding-bottom--24">
                                        <button type="button" id="login-btn" class="Recharge-button">Login</button>
                                    </div>
                                    <div class="register-link text-center">
                                        <p>Don't have an account? <a href="/Register" id="register-link">Register Here</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.toggle-password').click(function () {
            var passwordInput = $('#password');
            var icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#user_name').on('input', function () {
            $('#user-name-error').text('');
        });

        $('#password').on('input', function () {
            $('#password-error').text('');
        });

        $('#login-btn').click(function () {
            var userName = $('#user_name').val();
            var password = $('#password').val();

            if (!userName) {
                $('#user-name-error').text('User Name is required').css('color', 'red');
            }

            if (!password) {
                $('#password-error').text('Password is required').css('color', 'red');
            }

            if (userName && password) {
                var formData = new FormData();
                formData.append('user_name', userName);
                formData.append('password', password);

                $.ajax({
                    url: '/ValidateUser',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                         if(response.status==false){
                            $('#message').text(response.message).css('color', 'red');

                         }
                         else if(response.status==true){
                            window.location='Home'
                         }
                    },
                    error: function (xhr, status, error) {
                        alert('Invalid username or password');
                    }
                });
            }
        });
    });
</script>


</html>
