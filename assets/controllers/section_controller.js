import {Controller} from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['section1', 'section2', 'section3', 'section4','option1','option2']

    hideAll() {
        this.section1Target.style.display = 'none'
        this.section2Target.style.display = 'none'
        if (this.hasSection3Target) {
            this.section3Target.style.display = 'none'
        }
        if (this.hasSection4Target) {
            this.section4Target.style.display = 'none'
        }
    }

    connect() {
        this.hideAll()
        this.section1()
    }

    section1() {
        this.hideAll()
        this.section1Target.style.display = 'flex'
    }

    section2() {
        this.hideAll()
        this.section2Target.style.display = 'flex'
    }

    section3() {
        this.hideAll()
        this.section3Target.style.display = 'flex'
    }

    section4() {
        this.hideAll()
        this.section4Target.style.display = 'flex'
    }
    hideAllOptions(){
        this.option1Targets.forEach(option=>{
            option.style.display = 'none'
        })
        this.option2Targets.forEach(option=>{
            option.style.display = 'none'
        })
    }
    allOption(){
        this.option1Targets.forEach(option=>{
            option.style.display = 'flex'
        })
        this.option2Targets.forEach(option=>{
            option.style.display = 'flex'
        })
    }
    option1(){
        this.hideAllOptions()
        this.option1Targets.forEach(option=>{
            option.style.display = 'flex'
        })
    }
    option2(){
        this.hideAllOptions()
        this.option2Targets.forEach(option=>{

            option.style.display = 'flex'
        })
    }
}
