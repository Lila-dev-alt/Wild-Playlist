const hearts= document.getElementsByClassName('fa-heart');
const alertMessage = document.getElementById('alertMessage');
const heart = document.getElementById('heart');
for (let i=0; i < hearts.length; i++) {
    hearts[i].addEventListener('click',(event)=> {
        fetch('/likes/add',{
            method: 'POST',
            headers:  {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'playlist' : event.target.dataset.playlistid,
                'user' : event.target.dataset.userid
            })
        })
            .then(response => response.json())
            .then( data => alertMessage.classList.remove('hidden'),heart.classList.add('hidden'));
    })
}