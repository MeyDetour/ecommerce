import {Controller} from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['progressBar', 'rond2']
    static values = {"progress": Number, 'goal': Number}

    connect() {

        let value = (this.progressValue / this.goalValue) * 100
        this.progressBarTarget.style.width = `${value}%`

        if (value === 100){
            console.log(value)
            this.rond2Target.style.border = '2px solid #0FA4D8'
            this.rond2Target.style.background = '#8BDEFF'
        }
    }
}
