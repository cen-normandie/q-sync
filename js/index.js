
function toTitleCase(str){return str.replace(/\w\S*/g,function(txt){return txt.charAt(0).toUpperCase()+txt.substr(1).toLowerCase()})}

function check_mail(mail_v){
    var regex=/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if(!regex.test(mail_v)){
        if (mail_v != "") {
            alert('format du courriel non valide (xxx@xxx.xx)');
            return false
            }
        if (mail_v == "") {
            alert('courriel vide');
            return false
            }
        }
    else{

        return true
        }
    }

function check_pwd(pwd_v){
    var regex=/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!#?&_]{8,}$/;
    if(!regex.test(pwd_v)){
        if (pwd_v != "") {
            //$('#pwd').val('');
            alert('format du mot de passe non valide :\n- 8 caractères\n- 1 caractère spécial\n- 1 numéro');
            $("pwd").val('');
            $("#inscription_pwd").val('');
            return false
            }
        if (pwd_v == "") {
            alert('mot de passe vide');
            return false
            }
        }
    else{
        return true
        }
    };



function check_nom(nom_v){
    if (nom_v == "") {
        alert('Nom vide');
        return false
        }
    else{
        return true
        }
    };

function check_prenom(prenom_v){
    if (prenom_v == "") {
        alert('Prenom vide');
        return false
        }
    else{
        return true
        }
    };

$("#signin").click( function () {
    {
        $.ajax({
            url      : "php/login.php",
            type     : "POST",
            data     : {email: $("#courriel").val(), password:$("#pwd").val()},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data == "Success")
                {   
                    window.location.href = 'template.php';
                }
                else /*(data == "Failed")*/
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
            }
        });// End ajax
    }
});

