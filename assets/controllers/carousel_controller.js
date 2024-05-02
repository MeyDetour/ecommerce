import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['fullImage','minImage']
    connect(){
        this.hideAll()
        this.fullImageTargets[0].style.display = 'block'
        this.minImageTargets.forEach((image,index)=>{
           image.addEventListener('click',()=>{
               this.hideAll()
               this.fullImageTargets[index].style.display = 'block'
           })
        })
    }
    hideAll(){
        this.fullImageTargets.forEach((image)=>{

                image.style.display = 'none'

        })
    }
}
