const $ = document.querySelector.bind(document)
const $$ = document.querySelectorAll.bind(document)

$$('form').forEach((element) => {
  console.log({element})
});

function setActive(content_1, content_2) {
  content_1.classList.toggle('active');
  content_2.classList.toggle('active');

}

$$('.container-content-button').forEach(element => {
  element.addEventListener('click', (e) => {
    const parent = e.target.closest('.container')
    const notActive = parent.querySelector('.content:not(.active)')
    const active = parent.querySelector('.content.active')
    setActive(notActive, active)
  })
});

// function setStorage(){
//   localStorage.setItem();
// }
