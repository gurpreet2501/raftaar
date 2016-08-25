<div id="content">
    <div id="content-header">
        <h1><?= $heading ?></h1>
    </div> <!-- #content-header --> 
    <?php if(!count($output)):  ?>
       <h1 class="text-center"> No technicians found </h1>
    <?php return; endif; ?>   
    <div id="content-container">
      <table class="table">
        <tr>
           <th>First name</td>
           <th>Last name</td>
           <th>Social Security Number</td>
        </tr>

      <?php foreach ($output as $key => $value) { ?>
      <tr>
         <td><?=$value->first_name?></td> 
         <td><?=$value->last_name?></td> 
         <td><?=$value->social_security_number?></td> 
      </tr>
     <? } ?>
      </table>  
  </div> <!--content-container -->
</div> <!-- content -->  
