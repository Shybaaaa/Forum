
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div>
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const config = {
  type: 'doughnut',
  data: data,
};
    const data = {
      labels: ['Fondateurs', 'Membres', 'Administrateur', 'Modérateurs',],    
      datasets: [{
        backgroundColor:[
        'rgb(253, 232, 232)',
        'rgb(222, 247, 236)',
        'rgb(253, 246, 178)',
        'rgb(225, 239, 254)'
      ],
        label: 'nombre d utilisateur',
        data: [25, 25, 25, 25],
        options: {
          responsive: true,
      }
    }]
      
  };
</script>

<!-- const config = {
  type: 'doughnut',
  data: data,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Doughnut Chart'
      }
    }
  },
}; 

const DATA_COUNT = 5;
const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

const data = {
  labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
  datasets: [
    {
      label: 'Dataset 1',
      data: Utils.numbers(NUMBER_CFG),
      backgroundColor: Object.values(Utils.CHART_COLORS),
    }
  ]
}; -->