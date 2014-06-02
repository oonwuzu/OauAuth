
<!DOCTYPE html>
<?php
$mainurl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&scope=https://www.googleapis.com/auth/admin.directory.user&redirect_uri=http://www.swifta.co/oauauth/&client_id=872938699767.apps.googleusercontent.com&state=initial&approval_prompt=force&login_hint=googleapps@oauife.edu.ng';
?>
<a href="<?php echo $mainurl ?>">Connect Me</a>
<form id="login" action="https://accounts.google.com/o/oauth2/token" method="POST" >
    <div class="verification_div">
        <input type="hidden" id="code" name="code" value="<?php echo $_GET['code'] ?>"/>
        <input type="hidden" id="client_id" name="client_id" value="872938699767.apps.googleusercontent.com"/>
        <input type="hidden" id="client_secret" name="client_secret" value="rUUwTTv6xCZz-CwTnA7M0P8D"/>
        <input type="hidden" id="redirect_uri" name="redirect_uri" value="http://www.swifta.co/oauauth/"/>
        <input type="hidden" id="grant_type" name="grant_type" value="authorization_code"/>
        <input type="submit" name="snazzy_button" title="Enter a valid registration number" value="Retrieve Code"/>
    </div>
</form>
<script src="js/jquery.js"></script>
<script type="text/javascript">
    $(function() {
        $("#snazzy_button").click(function() {
            var authcode = $("#code").val();
            authcodeurl = 'https://accounts.google.com/o/oauth2/token HTTP/1.1';
            $.ajax({
                type: "POST",
                url: authcodeurl,
                data: {
                    'code': authcode,
                    'client_id': '872938699767.apps.googleusercontent.com',
                    'client_secret': 'rUUwTTv6xCZz-CwTnA7M0P8D',
                    'redirect_uri': 'http://www.swifta.co/oauauth/',
                    'grant_type': 'authorization_code'
                },
                //        data:  '&code=' + authcode + '&client_id=872938699767.apps.googleusercontent.com&client_secret=rUUwTTv6xCZz-CwTnA7M0P8D&redirect_uri=http://www.swifta.co/oauauth/&grant_type=authorization_code',
                cache: false,
            }).success(function(data, text) {
                alert(data);
                //       $("#login").submit();

            }).error(function(request, status, error) {
                var strvalu = JSON.stringify(request);
                alert("Could not validate email address. Try again later." + strvalu + " ====req====" + status + "=======status======" + error + "===error====");
            });

        });
    });
</script>