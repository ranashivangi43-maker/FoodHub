$("#registerForm").submit(function(e){
    e.preventDefault();
    let valid=true;
    let name=$("input[name='name']").val().trim();
    let email=$("input[name='email']").val().trim();
    let password=$("input[name='password']").val().trim();
    let confirm=$("input[name='confirm_password']").val().trim();
    $(".text-danger").text("");
    if(name.length<3){
       $("#nameError").text("Name too short");
       valid=false;
    }
    let emailPattern= /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)){
        $("#emailError").text("Invalid email");
        valid=false;
    }
    if(password.length<3){
        $("#passwordError").text("Password must be at least 3 characters");
        valid=false;
    }
     if(password!==confirm){
        $("#confirmError").text("Passwords do not match");
        valid=false;
    }
     if(!valid){
        e.preventDefault();
        return;
    }
    let form=$(this);
    let submitbtn=form.find("button[type='submit']");
    submitbtn.prop("disabled",true);
    $.ajax({
        url:"process/register_process.php",
        type:"POST",
        data:form.serialize(),
        success:function(response){
            if(response.trim()==="success"){
                form.trigger("reset");
                let modal =
bootstrap.Modal.getInstance(
document.getElementById('registerModal')
);

modal.hide();

setTimeout(function(){

    $("#registerMessage").html(`
        <div class="alert alert-success alert-dismissible fade show">
            Registration Successful
            <button 
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    `);

}, 500);

            }
            else{
                alert(response);
            }
            submitbtn.prop("disabled", false);
        }
    });
   
});

// login form script
$("#loginForm").submit(function(e){

    e.preventDefault();

    let email = $("#loginForm input[name='email']").val().trim();
    let password = $("#loginForm input[name='password']").val().trim();

    $("#loginEmailError").text('');
    $("#loginPasswordError").text('');

    let isValid = true;
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Email validation
    if(email === ''){

        $("#loginEmailError").text("Email is required");
        isValid = false;

    }
    if (!regex.test(email)) {
        $("#loginEmailError").text("Please enter a valid email address.");
        isValid = false;
        }

    // Password validation
    if(password === ''){

        $("#loginPasswordError").text("Password is required");
        isValid = false;

    }

    // Stop form
    if(!isValid){
        return;
    }

    let form = $(this);

    $.ajax({

        url:"process/login_process.php",
        type:"POST",
        data:form.serialize(),

        success:function(response){

            response = response.trim();

            if(response === "admin"){

                window.location.href = "admin/dashboard.php";

            }
            else if(response === "restaurant"){

                window.location.href = "restaurant/dashboard.php";

            }
            else if(response === "user"){

                window.location.href = "user/dashboard.php";

            }
            else{

                $("#loginPasswordError").text(response);

                $('input[name="password"]').val('');

            }

        }

    });

});