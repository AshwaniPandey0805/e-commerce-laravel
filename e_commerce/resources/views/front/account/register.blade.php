@extends('front.layouts.app')
@section('home-content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.index') }}">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form action="#" method="post" id="registerForm" name="registerForm" >
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                    <p></p>
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div> 
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>			
            <div class="text-center small">Already have an account? <a href="{{ route('front.login') }}">Login Now</a></div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script>
        $('#registerForm').submit(function(event){
            event.preventDefault();
            $("button[type='submit']").prop("disabled", true);
            var formArray = $(this).serialize();
            
            $.ajax({
                url : "{{ route('front.registerProcess') }}",
                type : 'POST',
                data : formArray,
                dataType : 'json',
                success : function ( response ){
                    var errors = response['errors'];

                    if(response['status'] ==  false){
                        $("button[type='submit']").prop("disabled", false); 
                        if(errors['name']){
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if(errors['email']){
                            $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if(errors['phone']){
                            $("#phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['phone']);
                        } else {
                            $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if(errors['password']){
                            $("#password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['password']);
                        } else {
                            $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                    } else {

                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    }

                    if(response['status'] ==  true){
                        $("button[type='submit']").prop("disabled", false);
                        window.location.href = "{{ route('front.login') }}"
                    }

                },
                error : function() {
                    alert('error handling here')
                }
            });

        });
    </script>
@endsection