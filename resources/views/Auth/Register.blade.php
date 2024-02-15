<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .register-form {
            background-color: #f8f9fa;
            padding: 50px 0;
        }

        .formbg {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .Recharge-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .Recharge-button:hover {
            background-color: #0056b3;
        }

        .alert {
            display: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: .75rem 1.25rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
        .error-message{
            color:red;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <div class="formbg">

            <div class="form-title">Register</div>
            <form id="stripe-register">
                <div class="field">
                    <label for="register_user_name">User Name</label>
                    <input type="text" name="register_user_name" id="register_user_name"
                        placeholder="Enter the User Name">
                    <div class="error-message" id="error-register_user_name"></div>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your Email">
                    <div class="error-message" id="error-email"></div>
                </div>
                <div class="field">
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number"
                        placeholder="Enter your Mobile Number">
                    <div class="error-message" id="error-mobile_number"></div>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"  autocomplete="off" placeholder="Enter your password">
                    <div class="error-message" id="error-password"></div>
                </div>
                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" placeholder="Confirm your password">
                    <div class="error-message" id="error-confirm_password"></div>
                </div>

                <div class="field">
                    <button type="button" id="register-btn" class="Recharge-button">Register</button>
                </div>
            </form>
            <div class="existing-account-link">
                Already have an account? <a href="/Login">Login</a>
            </div>
        </div>
    </div>
<script>
$(document).ready(function() {
    var isUserNameValid = true;

    $('#register_user_name').on('input', function() {
        var user_name = $('#register_user_name').val();
        $.ajax({
            url: '/Check-Name-Available',
            method: 'POST',
            data: {
                user_name: user_name,
            },
            processData: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if(response.status === false) {
                    $('#error-register_user_name').text('User Name Already Found, try Another');
                    isValid = false;
                    isUserNameValid = false;
                } else {
                    isUserNameValid = true;
                    $('#error-register_user_name').text('');
                }
            },
            error: function (xhr, status, error) {
                alert('Invalid username or password');
            }
        });
    });


    $('#password').on('input', function() {
        var password = $(this).val();
        var confirmPassword = $('#confirm_password').val();

        if (password.length < 8) {
            $('#error-password').text('Password must be at least 8 characters long');
        } else {
            $('#error-password').text('');
        }

        if (confirmPassword && password !== confirmPassword) {
            $('#error-confirm_password').text('Passwords do not match');
        } else {
            $('#error-confirm_password').text('');
        }
    });

    $('#confirm_password').on('input', function() {
        var password = $('#password').val();
        var confirmPassword = $(this).val();

        if (password !== confirmPassword) {
            $('#error-confirm_password').text('Passwords do not match');
        } else {
            $('#error-confirm_password').text('');
        }
    });

    $('#mobile_number').on('input', function() {
        var mobileNumber = $(this).val();
        if (mobileNumber.length !== 10) {
            $('#error-mobile_number').text('Mobile Number must be 10 digits');
        } else {
            $('#error-mobile_number').text('');
        }
    });

    $('#register-btn').click(function() {
        var userName = $('#register_user_name').val();
        var email = $('#email').val();
        var mobileNumber = $('#mobile_number').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();

        $('.error-message').text('');
        var isValid = true;
        if (!userName) {
            $('#error-register_user_name').text('User Name is required');
            isValid = false;
        }
        if (!email) {
            $('#error-email').text('Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#error-email').text('Enter a valid email');
            isValid = false;
        }

        if (!isUserNameValid) {
            $('#error-register_user_name').text('User Name is not available');
            isValid = false;
        }
        if (!mobileNumber) {
    $('#error-mobile_number').text('Mobile Number is required');
    isValid = false;
} else if (isNaN(mobileNumber)) {
    $('#error-mobile_number').text('Mobile Number must be a number');
    isValid = false;
} else if (mobileNumber.length !== 10) {
    $('#error-mobile_number').text('Mobile Number must be 10 digits');
    isValid = false;
}

        if (!password) {
            $('#error-password').text('Password is required');
            isValid = false;
        } else if (password.length < 8) {
            $('#error-password').text('Password must be at least 8 characters long');
            isValid = false;
        }
        if (!confirmPassword) {
            $('#error-confirm_password').text('Confirm Password is required');
            isValid = false;
        }
        if (password !== confirmPassword) {
            $('#error-confirm_password').text('Passwords do not match');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
    url: '/create-User',
    method: 'POST',
    data: $.param({
        user_name: userName,
        mobile_number: mobileNumber,
        email: email,
        password: password
    }),
    processData: true,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function (response) {
    if (response.status === true) {
        Swal.fire({
            icon: "success",
            title: response.status,
            text: response.message,
            footer: ''
        });
        setTimeout(function() {
            window.location='/Login';
        }, 2000);
    } else {
        Swal.fire({
            icon: "error",
            title: response.status,
            text: response.message,
            footer: ''
        });
    }
    },
    error: function (xhr, status, error) {
        console.log('error');
    }
});

}

    });
});

function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

</script>
</body>
