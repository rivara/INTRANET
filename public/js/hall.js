$(document).ready(function() {

    var $rows = $("tr");

    $(".busca").keyup(function() {
        var val = $.trim(this.value);
        if (val === "")
            $rows.show();
        else {
            $rows.hide();
            $rows.has("td:contains(" + val + ")").show();
            $rows.has("th").show();
        }
    });


/*actions*/

$(".delete").click(function(){
     var butval = $(this).val();
     $("#id").val(butval);
});


    $(".icon").on('click', function() {

            if (!$(this).hasClass("expanded")) {
                $(".iconModal").css("display","none");
                $(this).addClass("expanded");
            }
            else {
                $(".iconModal").css("display","block");
                $(this).removeClass("expanded");
            }
        });

        //$(".iconModal").css("visibility","visible");
    //});


/*modifies*/
/* modifica la entrada */


    $("#email").keypress(function()
    {
        $(".invalid-feedback").css('display','none');
        $(this).css('outline','none');
        $(this).css(' border','none');
        $(this).css(' -webkit-box-shadow','none');
        $(this).css('-moz-box-shadow','none');
        $(this).css('box-shadow','none');
        $(this).css('border-color','#ced4da');
    });

    $("#password").keypress(function()
    {
        $(".invalid-feedback").css('display','none');
        $(this).css('outline','none');
        $(this).css(' border','none');
        $(this).css(' -webkit-box-shadow','none');
        $(this).css('-moz-box-shadow','none');
        $(this).css('box-shadow','none');
        $(this).css('border-color','#ced4da');
    });



    $.get('https://raw.githubusercontent.com/FortAwesome/Font-Awesome/fa-4/src/icons.yml', function(data) {
        var parsedYaml = jsyaml.load(data);
        $.each(parsedYaml.icons, function(index, icon){
            $('#select').append('<option value="fa-' + icon.id + '">' + icon.id + '</option>');
        });

        $("#select").chosen({
            enable_split_word_search: true,
            search_contains: true
        });
        $("#icon").html('<i class="fa fa-2x ' + $('#select').val() + '"></i>');
    });

    /* Detect any change of option*/
    $("#select").change(function(){
        var icono = $(this).val();
        $("#icon").html('<i class="fa fa-2x ' + icono + '"></i>');
    });
     //persiana de mensaje
    $("#alert").fadeTo(2000, 500).slideUp(500, function(){$("#alert").slideUp(500);});

    $('form input').change(function () {
        $('form p').text(this.files.length + " file(s) selected");
    });

/*MENU*/
/*
    $(".subMenu").click(function(){
          //  e.preventDefault();
            var text = $(this).attr('value');
           // alert(text);
        $.ajax({
            type: "POST",
            url: "/update",
            data:text,
            success:function(){alert('success!');},
            error: function (){alert('error');},


        });
    });
*/




});






