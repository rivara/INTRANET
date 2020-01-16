$(function() {

    window.prettyPrint && prettyPrint();

    $('#colorselector_1').colorselector();
    $('#colorselector_2').colorselector({
        callback : function(value, color, title) {
            $("#colorValue").val(value);
            $("#colorColor").val(color);
            $("#colorTitle").val(title);
        }
    });

    $("#setColor").click(function(e) {
        $("#colorselector_2").colorselector("setColor", "#008B8B");
    })

    $("#setValue").click(function(e) {
        $("#colorselector_2").colorselector("setValue", 18);
    })

});
