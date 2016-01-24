<body>
  <div class ="jumbotron">
   <div class = "container">
      <h2> Stats correcteur </h2>
      <table class ="table">
        <thead>
          <tr>
            <th> Nom </th>
            <th> ID Correcteur </th>
            <th> Nombre de copies restantes </th>
            <th> Mail </th>
          </tr>
        </thead>
        <tbody>
          <?php
           include("../database_request/remplirtableau.php")
           ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
