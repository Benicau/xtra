function paginationBlanc() {
    const contenu = document.querySelectorAll('.content');
    
    let sortedData = {
        A0A1A2: [],
        A3: [],
        A4: []
    };

    contenu.forEach((element) => {
        const inputInsideContent = element.querySelector('input');
        
        if (inputInsideContent) {
            const datacat = inputInsideContent.getAttribute('datacat');
            
            if (datacat === 'Blanc') {
                const dataname = inputInsideContent.getAttribute('dataname');
                const dataprice = inputInsideContent.getAttribute('dataprice');
                element.style.display = 'none';
                
                let contentHTML = `<div class="content accord"><p>${dataname}</p><p>${dataprice}€</p><input type="number" value=0 dataprice=${dataprice} dataname="${dataname}" datacat="${datacat}"></div>`;
                if (dataname.includes('A0') || dataname.includes('A1') || dataname.includes('A2')) {
                    sortedData.A0A1A2.push(contentHTML);
                } else if (dataname.includes('A3')) {
                    sortedData.A3.push(contentHTML);
                } else if (dataname.includes('A4')) {
                    sortedData.A4.push(contentHTML);
                }
            }
        }
    });

    const tabsContainer = document.querySelector('.tabs');
    const tabsContentContainer = document.querySelector('.tabs-content');

    for (let tab in sortedData) {
        const tabElement = document.createElement('button');
        tabElement.innerText = tab;
        tabElement.classList.add('tab-button');
        
        tabElement.addEventListener('click', function(e) {
            e.preventDefault();

            // Retirez la classe 'active' de tous les onglets
            document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));

            // Ajoutez la classe 'active' à l'onglet cliqué
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            document.querySelector(`.tab-content-${tab}`).style.display = 'block';
        });
        
        const tabContent = document.createElement('div');
        tabContent.classList.add(`tab-content`, `tab-content-${tab}`);
        tabContent.innerHTML = sortedData[tab].join('');
        tabContent.style.display = 'none';

        tabsContainer.appendChild(tabElement);
        tabsContentContainer.appendChild(tabContent);
    }

    // Si un onglet existe, définissez le premier onglet comme actif par défaut
    if (tabsContainer.firstChild) {
        tabsContainer.firstChild.click(); // Simuler un clic sur le premier bouton pour activer l'onglet et appliquer les styles
    }
}







// Function for calculating the total cost and updating the display
  function calcul() {  
    var texte = ''
    const datas1 = document.querySelectorAll('.content input');
var total = 0;
datas1.forEach((input) => {
    var value = input.value;
    const data = input.getAttribute('dataprice');
    const dataname = input.getAttribute('dataname');
    const cat = input.getAttribute('datacat');

    if (value === '') {
        value = 0;
    } else if (parseFloat(value) < 0) {
        value = 0;
    } 

    if (value > 0) {
        texte += '<p>' + value + ' ' + cat + ' ' + dataname + ' (' + data + '€) = <span>' + parseFloat(value * data).toFixed(2) + '€</span></p>';
    }
    
    total += data * value;
    
    if (total < 0) {
        total = 0;
    }
});


    const autre = document.querySelector('.autre .center input')
    var value2
    value2=parseFloat(autre.value)
    if(isNaN(value2))
    {
        value2=0
    }  
    if(value2>0){
        texte=texte+'<p>Divers services Xtra-copy = <span>'+value2+'€</span></p>'
    }

    
    
    const dataNb = document.querySelectorAll('.data-nb')
    const dataColor = document.querySelectorAll('.data-color')
    const nbCopie = document.querySelector('#nb_noir_blanc')
    const colorCopie = document.querySelector('#nb_couleurs')
    var nbitem = parseInt(nbCopie.value)
    var coloritem = parseInt(colorCopie.value)
    var priceColor = 0
    var priceNb = 0
    dataNb.forEach(nb=>{
        const nbstart = parseInt(nb.getAttribute('data-start'));
        const nbend = parseInt(nb.getAttribute('data-end'));
        const nbprice = parseFloat(nb.getAttribute('data-price'));
        if((nbitem >= nbstart)&&(nbitem <= nbend))
        {
            priceNb = nbprice 
            texte=texte+'<p>Nombre de copies N/B est de : '+nbitem+' * ('+priceNb+'€) = <span>'+(nbitem*priceNb).toFixed(2)+'€</span></p>'
        }
    })

    dataColor.forEach(color=>{
        const colorstart = parseInt(color.getAttribute('data-start'));
        const colorend = parseInt(color.getAttribute('data-end'));
        const colorprice = parseFloat(color.getAttribute('data-price'));
        if((coloritem >= colorstart)&&(coloritem <= colorend))
        {
            priceColor = colorprice
            texte=texte+'<p>Nombre de copies couleur est de : '+coloritem+' * ('+priceColor+'€) = <span>'+(coloritem*priceColor).toFixed(2)+'€</span></p>'
        }  
    })

    var total2 = (nbitem*priceNb) + (coloritem*priceColor)


    total=total+value2+total2
    const affichage = document.querySelector('.total p span')
    var resultat = total.toFixed(2)
    if (isNaN(resultat)) {
        resultat=0
    } 
    affichage.innerHTML= resultat


    const affichCaisse = document.querySelector('#affichage')
    affichCaisse.innerHTML= texte
    const textInvoice = document.querySelector('#invoice_form_texte')
    textInvoice.value= texte
    const totalInvoice = document.querySelector('#invoice_form_total')
    totalInvoice.value = total

    // Enable/disable the "send" button based on the total cost
    const send = document.querySelector(".send")
    send.disabled = true

    if(resultat>0)
    {
        send.disabled = false
    }
  }


  // Initialize calculations when the page is loaded
  document.addEventListener('DOMContentLoaded', function() {
    paginationBlanc();
    function handleInputValue(input) {
        if (input.value < 0 || input.value === '') {
            input.value = 0;
        }
        calcul();
    }
    
    const onglets = document.querySelectorAll('.onglets');
    const contenu = document.querySelectorAll('.contenu');
    
    onglets.forEach((onglet, idx) => {
        onglet.addEventListener('click', () => {
            // Supprime la classe active de tous les onglets et contenu
            onglets.forEach(tab => tab.classList.remove('active'));
            contenu.forEach(content => content.classList.remove('activeContenu'));
    
            // Ajoute la classe active à l'onglet et contenu cliqué
            onglet.classList.add('active');
            contenu[idx].classList.add('activeContenu');
        });
    });
    
    const inputs = document.querySelectorAll('.content input');
    const divers = document.querySelector('.autre .center input');
    const nbCopie = document.querySelector('#nb_noir_blanc');
    const colorCopie = document.querySelector('#nb_couleurs');
    
    [nbCopie, colorCopie, divers].forEach(inputElement => {
        inputElement.addEventListener('input', () => handleInputValue(inputElement));
    });
    
    inputs.forEach(input => {
        input.addEventListener('input', () => handleInputValue(input));
    });
    calcul ();
});