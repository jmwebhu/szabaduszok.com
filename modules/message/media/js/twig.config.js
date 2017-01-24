/*

Usage:
$("body").append(
    twig({ref: 'include'}).render({valami: 'alma'})
);
*/

$("script[type='text/twig']").each(function() {
    var id = $(this).attr("id");
    var data = $(this).text();

    twig({
        id: id,
        data: data,
        allowInlineIncludes: true
    });
});