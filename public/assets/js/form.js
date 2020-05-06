let inputs = document.getElementsByClassName('labeleur');

for(let i = 0; i <inputs.length; i++) {
    if (inputs[i].value !="" ) {
        inputs[i].classList.add('active')
    }
}
for(let i = 0; i <inputs.length; i++) {
    inputs[i].addEventListener('change',(e)=> {
        e.target.classList.add('active')
    })
}