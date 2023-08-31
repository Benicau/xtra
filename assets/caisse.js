const onglets = document.querySelectorAll('.onglets')
const contenu = document.querySelectorAll('.contenu')
let index = 0

// Loop through each tab element
onglets.forEach(onglet => {
    onglet.addEventListener('click', ()=>{
        // Check if the clicked tab is already active
        if(onglet.classList.contains('active')){
            return;
        }
        else{
            onglet.classList.add('active')
        }

        // Update index based on the clicked tab
        index = onglet.getAttribute('data-anim')
        for (i=0; i < onglets.length; i++ )
        {
           if(onglets[i].getAttribute('data-anim') != index) 
           {
             onglets[i].classList.remove('active')
           }
        }

        // Update content visibility based on index
        for(j=0; j<contenu.length; j++){
            if(contenu[j].getAttribute('data-anim')== index) {
                contenu[j].classList.add('activeContenu')
            }
            else {
                contenu[j].classList.remove('activeContenu')
            }


        }
    })
})


// Handling input elements for copy options and various services
const inputs = document.querySelectorAll('.content input');
const divers = document.querySelector('.autre .center input');
const nbCopie = document.querySelector('#nb_noir_blanc')
const colorCopie = document.querySelector('#nb_couleurs')

// Ensure non-negative values
nbCopie.addEventListener('input', function(){
    if(nbCopie.value<0)
    {
        nbCopie.value=0
    }
    

    calcul ();

})

// Ensure non-negative values
colorCopie.addEventListener('input', function(){
    if(colorCopie.value<0)
    {
        colorCopie.value=0
    }
    calcul ();
})


// Ensure non-negative values
divers.addEventListener('input', function(){
    if(divers.value<0)
    {
        divers.value=0
    }  
    calcul ();
})

// Ensure non-negative values
inputs.forEach((input) => {
    input.addEventListener('input', function() {
      
      if(input.value<0)
      {
          input.value=0
      }
      calcul ();
    });
  });

// Function for calculating the total cost and updating the display
  function calcul() {  
    var texte = ''
    const datas = document.querySelectorAll('.content input');
    var total = 0;
    datas.forEach((input) => {
        var value = input.value;
        const data = input.getAttribute('dataprice');
        const datas = input.getAttribute('dataname');
        const cat = input.getAttribute('datacat');
        if (value=== '' )
        {
            value=0
        }
        else if(parseFloat(value) < 0) {
            value=0
            
        } 
        if(value>0){
            texte=texte+'<p>'+value+' '+cat+' '+datas+' ('+data+'€) = <span>'+value*data+'€</span></p>'
        }
    total = total + (data*value) 
 
    
    if(total <0 )
    {
        total=0;
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
    calcul ();
});