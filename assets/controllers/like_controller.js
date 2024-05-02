import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {'productId':Number}
    connect() {
    }

    like(){

        let id = this.productIdValue; // Assurez-vous que l'ID est correctement défini en tant qu'attribut data-id sur l'élément.
        console.log(id);

        fetch(`/like/product/${id}`).then(response=>response.json()).then(data=>{
            const ic = this.element.querySelector('i');

            if (data.isLiked === true) {
                ic.classList.remove('bi-bag');
                ic.classList.add('bi-bag-heart-fill');
            } else {
                ic.classList.remove('bi-bag-heart-fill');
                ic.classList.add('bi-bag');
            }
        }).catch(error => {
            console.error('Erreur lors de la mise à jour du like:', error);
        });
    }
}
