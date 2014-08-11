<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
        {{ HTML::style('http://yui.yahooapis.com/pure/0.5.0/pure-min.css'); }}
        {{ HTML::style('css/creekfish.css'); }}

        {{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'); }}
        {{ HTML::script('js/creekfish.js'); }}
        {{ HTML::script('js/creekfish/users.js'); }}
    </head>
	<body>
		<h2>User Registration</h2>

        <div id='info-callout'></div>

        <div id='error-callout'></div>

		<div class='user-registration' id='user-registration-container'>
            {{ Form::open(array('url' => '/api/v1/users', 'class' => 'pure-form pure-form-aligned', 'id' => 'user-form')) }}
                <div class="pure-control-group">
                {{ Form::label('email', 'E-Mail:', array('class' => 'label')) }}
                {{ Form::text('email', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                {{ Form::label('password', 'Password:', array('class' => 'label')) }}
                {{ Form::password('password', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                    {{ Form::label('password-confirm', 'Confirm Password:', array('class' => 'label')) }}
                    {{ Form::password('password-confirm', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                {{ Form::label('first_name', 'First Name:', array('class' => 'label')) }}
                {{ Form::text('first_name', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                {{ Form::label('last_name', 'Last Name:', array('class' => 'label')) }}
                {{ Form::text('last_name', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                    {{ Form::label('city', 'City:', array('class' => 'label')) }}
                    {{ Form::text('city', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                    {{ Form::label('state', 'State:', array('class' => 'label')) }}
                    {{ Form::select('state', App::make('\Creekfish\Models\Enums\State')->toArray()) }}
                </div>
                <div class="pure-control-group">
                    {{ Form::label('zip', 'Zip Code:', array('class' => 'label')) }}
                    {{ Form::text('zip', null, array('class' => 'field')) }}
                </div>
                <div class="pure-control-group">
                {{ Form::label('biography', 'Biography:', array('class' => 'label')) }}
                {{ Form::textarea('biography', null, array('class' => 'field-big')) }}
                </div>
                {{ Form::submit('Register', array('class' => 'pure-button')) }}
            {{ Form::close() }}
		</div>

        <script>

            // to save time, scripting directly in HTML body, but would put everything in JS files

            $( "#user-form" ).submit(function( event ) {
                event.preventDefault();

                var form = $(this);
                var Users = CREEKFISH.Users;  // simple JS namespaced helper object

                Users.hideErrors();

                // ensure passwords match
                if (!Users.passwordsMatch(
                        form.find( "input[name='password']" ).val(),
                        form.find( "input[name='password-confirm']" ).val())
                    ) {
                    Users.showErrors(["Passwords do not match."]);
                    return;
                }

                // post the form data and handle success and failure
                $.post(form.attr('action'), form.serialize(), null, 'json')

                    .done(function(json) {
                        Users.submitOk(json.data);
                    })

                    .fail(function(json) {
                        Users.submitFailed(JSON.parse(json.responseText).data);
                    });
            });
        </script>
	</body>
</html>