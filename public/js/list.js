// jQuery UI autocomplete extension - suggest labels may contain HTML tags
// github.com/scottgonzalez/jquery-ui-extensions/blob/master/src/autocomplete/jquery.ui.autocomplete.html.js
(function($){var proto=$.ui.autocomplete.prototype,initSource=proto._initSource;function filter(array,term){var matcher=new RegExp($.ui.autocomplete.escapeRegex(term),"i");return $.grep(array,function(value){return matcher.test($("<div>").html(value.label||value.value||value).text());});}$.extend(proto,{_initSource:function(){if(this.options.html&&$.isArray(this.options.source)){this.source=function(request,response){response(filter(this.options.source,request.term));};}else{initSource.call(this);}},_renderItem:function(ul,item){return $("<li></li>").data("item.autocomplete",item).append($("<a></a>")[this.options.html?"html":"text"](item.label)).appendTo(ul);}});})(jQuery);


function googleSuggest(request, response) {
    var term = request.term;
    // if (term in cache) { response(cache[term]); return; }
    /*$.ajax({
        url: 'http://query.yahooapis.com/v1/public/yql',
        dataType: 'JSONP',
        data: { format: 'json', q: 'select * from xml where url="http://google.com/complete/search?output=toolbar&q='+term+'"' },
        success: function(data) {
            var suggestions = [];
            try { var results = data.query.results.toplevel.CompleteSuggestion; } catch(e) { var results = []; }
            $.each(results, function() {
                try {
                    var s = this.suggestion.data.toLowerCase();
                    suggestions.push({label: s.replace(term, '<b>'+term+'</b>'), value: s});
                } catch(e){}
            });
            cache[term] = suggestions;
            response(suggestions);
        }
    });*/
}


$(function() {

    $('#autocomplete').tagEditor({
        placeholder: ' ...',
        autocomplete: { source: googleSuggest, minLength: 3, delay: 250, html: true, position: { collision: 'flip' } },
        onChange: tag_classes

    });


    function tag_classes(field, editor, tags) {
        $('li', editor).each(function(){
            var li = $(this);
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var t= li.text().length;
            var res = li.text().substring(2,t);

            if(regex.test(res)==false){
                li.addClass('red-tag');
            }
        });
    }


    $('#remove_all_tags').click(function() {
      //  for (i=0;i<tags.length;i++){ $('#demo3').tagEditor('removeTag', tags[i]); }
    });

});