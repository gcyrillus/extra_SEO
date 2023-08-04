window.onload=function() {
    
    
    const list = [...document.body.querySelectorAll('#form_extra_SEO .onglet')];
    if(list.length == 0) { return; }
    
    if(list.length > 1) {
        // On crée une barre de navigation s'il y a plus de 1 chapitre
        var innerHTML = '';
        list.forEach((item, i) => {
            const caption = item.getAttribute('data-title');
            innerHTML += `<button data-page="${i}">${caption}</button>`;
            item.setAttribute('id','onglet-'+i);
            let gotab = document.createElement("input");
            gotab.setAttribute('type','submit');
            gotab.setAttribute('id','#onglet-'+i);
            gotab.setAttribute('name','#onglet-'+i);
            gotab.setAttribute('value','Sauvegarder');
            item.appendChild(gotab);
        });
        
        // On crée la barre de navigation
        const pagination_numbers_container = document.createElement('NAV');
        pagination_numbers_container.className = 'art-nav center';
        pagination_numbers_container.innerHTML = innerHTML;
        
        const page0 = list[0].parentElement;
        page0.parentElement.insertBefore(pagination_numbers_container, page0);
        
        // On gére le click sur la barre de navigation
        pagination_numbers_container.addEventListener('click', (evt) => {
            if(evt.target.hasAttribute('data-page')) {
                evt.preventDefault();
                // On affiche uniquement le chapitre demandé
                [...document.body.querySelectorAll('.onglet.active')].forEach((item) => {
                    item.classList.remove('active');
                });
                const i = parseInt(evt.target.dataset.page);
                list[i].classList.add('active');
                // On met en évidence uniquement le bouton du chapitre affiché
                [...pagination_numbers_container.querySelectorAll('.active')].forEach((item) => {
                    item.classList.remove('active');
                });
                event.target.classList.add('active');
            } 
        });
    }
    
    // On allume sur le premier .onglet
    if(window.location.hash.substring(0, 1) !=='#') { 
        list[0].classList.add('active');
        const btn = document.body.querySelector('.art-nav button');
    }
    else { 
        let select ='.art-nav button[data-page="'+window.location.hash.substring(8)+'"]';
        list[window.location.hash.substring(8)].classList.add('active');
        const btn = document.querySelector(select);
    }
    if(btn != null) {
        btn.classList.add('active');
    }
}