(function(){
    function addTagForm(collectionHolder, $newLinkLi) {
        // Get the data-prototype we explained earlier
        var prototype = collectionHolder.attr('data-prototype'),

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on the current collection's length.
            newForm = prototype.replace(/__name_(_label_)?_/g, collectionHolder.children().length),

        // Display the form in the page in an li, before the "Add a tag" link li
            $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);
    }

    function addTagFormDeleteLink($tagFormLi) {
        var $removeFormA = $('<a href="#">delete this tag</a>');
        $tagFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $tagFormLi.remove();
        });
    }
    NinjaLunch.CollectionType = function(collectionHolder) {
        // setup an "add a tag" link
        var $addTagLink = $('<a href="#" class="btn add_tag_link">Add a tag</a>'),
            $newLinkLi  = $('<li></li>').append($addTagLink)
        ;

        console.log($addTagLink, collectionHolder);
        // add the "add a tag" anchor and li to the tags ul
        collectionHolder.append($newLinkLi);

        $addTagLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addTagForm(collectionHolder, $newLinkLi);
        });
    };
}(jQuery));
