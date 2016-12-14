/*

Usage:
$("body").append(
    twig({ref: 'include'}).render({valami: 'alma'})
);
*/

Twig.extendFilter("backwords", function(value) {
    console.log(this);
    return value.split(" ").reverse().join(" ");
});

Twig.extendFilter("price_format", function(value, precision) {
    if(! precision) precision = 0;
    return number_format(value, precision, '.', ' ') + ' Ft';
});

Twig.extendFilter('i18n', function(value) {
    return jsI18n.get(value);
});

$("script[type='text/twig']").each(function() {
    var id = $(this).attr("id");
    var data = $(this).text();

    twig({
        id: id,
        data: data,
        allowInlineIncludes: true
    });
});