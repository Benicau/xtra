const onglets = document.querySelectorAll('.onglets')
const contenu = document.querySelectorAll('.contenu')
let index = 0

onglets.forEach(onglet => {
    onglet.addEventListener('click', ()=>{
        if(onglet.classList.contains('active')){
            return;
        }
        else{
            onglet.classList.add('active')
        }

        index = onglet.getAttribute('data-anim')
        for (i=0; i < onglets.length; i++ )
        {
           if(onglets[i].getAttribute('data-anim') != index) 
           {
             onglets[i].classList.remove('active')
           }
        }

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


const inputs = document.querySelectorAll('.content input');
const divers = document.querySelector('.autre .center input');
const nbCopie = document.querySelector('#nb_noir_blanc')
const colorCopie = document.querySelector('#nb_couleurs')


nbCopie.addEventListener('input', function(){
    if(nbCopie.value<0)
    {
        nbCopie.value=0
    }
    calcul ();
})

colorCopie.addEventListener('input', function(){
    if(colorCopie.value<0)
    {
        colorCopie.value=0
    }
    calcul ();
})


divers.addEventListener('input', function(){
    if(divers.value<0)
    {
        divers.value=0
    }  
    calcul ();
})

inputs.forEach((input) => {
    input.addEventListener('input', function() {
      
      if(input.value<0)
      {
          input.value=0
      }
      calcul ();
    });
  });


  function calcul() {  

    const datas = document.querySelectorAll('.content input');
    var total = 0;
    datas.forEach((input) => {
        var value = input.value;
        const data = input.getAttribute('dataprice');
        if (value=== '' )
        {
            value=0
        }
        else if(parseFloat(value) < 0) {
            value=0
            
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
        }
    })

    dataColor.forEach(color=>{
        const colorstart = parseInt(color.getAttribute('data-start'));
        const colorend = parseInt(color.getAttribute('data-end'));
        const colorprice = parseFloat(color.getAttribute('data-price'));
        if((coloritem >= colorstart)&&(coloritem <= colorend))
        {
            priceColor = colorprice
        }  
    })

    var total2 = (nbitem*priceNb) + (coloritem*priceColor)
    
    













    total=total+value2+total2
    const affichage = document.querySelector('.total p span')
    const resultat = total.toFixed(2)
    affichage.innerHTML= resultat







  }


calcul ();





