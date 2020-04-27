let iframes = document.getElementsByTagName('iframe');
let selectors = document.getElementsByClassName('song');


for (let i = 0; i< selectors.length; i++){
  selectors[i].addEventListener('click', (e)=>{
      iframes[i].classList.toggle('hidden');
  })
}



