<?php
require_once "private/functions/functions.php";

session_start();

$variableAPasser = getUser($_SESSION ["user"]["id"]);

?>
<div class="chartBox"  style= "display: block; box-sizing: border-box; height: 250px; width: 250px;">
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script> var roles = <?php echo json_encode($variableAPasser); ?>; </script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'doughnut',
    data:   {
      labels: ['Fondateurs', 'Administrateur', 'Membres', 'Modérateurs',],    
      datasets: [{
        backgroundColor:[
        'rgb(253, 232, 232)',
        'rgb(222, 247, 236)',
        'rgb(253, 246, 178)',
        'rgb(225, 239, 254)'
      ],
        hoverOffset: 25,
        label: 'nombre d utilisateur',
        data: [25, 25, 25, 25],
        options: {
          responsive: true,
      }
    }]
    }
  });
</script>