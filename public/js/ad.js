// J'écoute les événements click sur le bouton add-image
$('#add-image').click(function(){
    // Je récupère le compte des widgets afin de gérer les index de mes images
    // le + impose à val d'etre un integer et non pas un string permet l'addition du count
    // évite que le compte soit 01 car id 01 ne fonctionnerait pas
    const index = +$('#widgets-counter').val();



    // Je récupère le prototype des entrées, je remplace chaque __name__ par
    // la valeur de index g veut dire plusieurs fois
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g,index);

    // J'injecte ce code dans la div grace à append
    $('#ad_images').append(tmpl);

    // On ajoute 1 a la valeur de notre widget counter apres la création d'un widget
    $('#widgets-counter').val(index + 1);

    // Je gère le bouton supprimer
    handleDeleteButtons();
});
function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        // This renvoi au bouton sur lequel on click dataset tous les attributs data quelques chose
        // le .target car ici on veut l'attribut data-target
        const target = this.dataset.target;
        // Je supprime la target
        $(target).remove();
    })
}

function updateCounter(){
    const count = $('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}
updateCounter();
// Bouton supprimer présent dès le chargement de la page
handleDeleteButtons();