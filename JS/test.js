
    
function nextQuestion(currentId, nextId) {
  // Ocultar la pregunta actual
  document.getElementById(currentId).style.display = "none";
  // Mostrar la siguiente pregunta
  document.getElementById(nextId).style.display = "block";
}

function submitForm() {
  alert("Formulario completado!");
}

 

  
 
  
 
  function submitWeight() {
    const weight = document.getElementById("weight").value;
    if (weight) {
        alert("Tu peso actual es: " + weight + " kg");
        // Aquí puedes almacenar el valor o enviarlo a otra función
    } else {
        alert("Por favor, ingresa tu peso.");
    }
}

function nextQuestion(currentId, nextId) {
  document.getElementById(currentId).style.display = "none";
  document.getElementById(nextId).style.display = "block";
}

