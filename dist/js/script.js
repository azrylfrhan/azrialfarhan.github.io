//hamburger

const hamburger = document.querySelector('#hamburger');
const navMenu = document.querySelector('#nav-menu');

hamburger.addEventListener('click', function() {
    hamburger.classList.toggle('hamburger-active');
    navMenu.classList.toggle('hidden');

});

//klik diluar hamburger
window.addEventListener('click', function(e){
    if(e.target != hamburger && e.target != navMenu){
        hamburger.classList.remove('hamburger-active');
        navMenu.classList.add('hidden');
    }


});


//Navbar Fixed
window.onscroll = function(){
    const header = document.querySelector('header');
    const fixedNav = header.offsetTop;

    if(window.pageYOffset > fixedNav) {
        header.classList.add('navbar-fixed');
    }else {
        header.classList.remove('navbar-fixed');
    }
}

//Section Start
    document.addEventListener("DOMContentLoaded", function () {
        // Menangkap semua elemen menu
        var menuItems = document.querySelectorAll('nav ul li a');
        // Iterasi melalui setiap elemen menu dan menambahkan event listener
        menuItems.forEach(function (menuItem) {
            menuItem.addEventListener('click', function (event) {
                event.preventDefault(); // Menghentikan perilaku bawaan dari tag <a>
                // Mendapatkan ID bagian yang sesuai dengan href menu yang diklik
                var targetSectionId = this.getAttribute('href').substring(1);
                var targetSection = document.getElementById(targetSectionId);
                // Memastikan bahwa section target ditemukan sebelum mengganti kelas
                if (targetSection) {
                    // Menyembunyikan semua section
                    var sections = document.querySelectorAll('section');
                    sections.forEach(function (section) {
                        section.classList.remove('active');
                    });
                    // Menampilkan section target
                    targetSection.classList.add('active');
                    // Memperbarui kelas aktif pada menu
                    menuItems.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                    // Opsional: Scroll ke section target
                    targetSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });



    //close object
    const closeBtn = document.querySelector('.close-btn');
    const closeAlert = document.querySelector('.close-alert');

    closeBtn.addEventListener('click', function() {
        closeAlert.classList.toggle('hidden');
    });

    //modal open



    //mengirim form ke spreadsheet
    const scriptURL = 'https://script.google.com/macros/s/AKfycbxDYeWZsOSBzVVHAXD-t5MmiLfIkQZXnMh9__FwgboMHa0OueIIFqtFPF_2Qy8zd1e9/exec';
    const form = document.forms['portfolio-contact-form'];
    const btnKirim = document.querySelector('.btn-kirim');
    const btnLoading = document.querySelector('.btn-loading');
    const myAlert = document.querySelector('.az-alert');
    

    form.addEventListener('submit', e => {
        e.preventDefault()
        //ketika submit diklik
        //tampilkan tombol loading hilangkan kirim
        btnLoading.classList.toggle('hidden');
        btnKirim.classList.toggle('hidden');
        fetch(scriptURL, { method: 'POST', body: new FormData(form)})
        .then(response => {
            //tampilkan tombol kirim hilangkan loading
            btnLoading.classList.toggle('hidden');
            btnKirim.classList.toggle('hidden');
            //tampilkan alert
            myAlert.classList.toggle('hidden');
            //reset form
            form.reset();
            console.log('Success!', response)
        })
        .catch(error => console.error('Error!', error.message))
    })




