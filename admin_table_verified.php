<table>
<tr>
<th>#</th><th>Pet</th><th>Breed</th><th>Contact</th><th>Type</th><th>Status</th>
</tr>

<?php $i=1;
$m=$conn->query("SELECT * FROM missing_pets WHERE status='Accepted'");
while($r=$m->fetch_assoc()){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['pet_name'] ?></td>
<td><?= $r['breed'] ?></td>
<td><?= $r['contact'] ?></td>
<td>Missing</td>
<td><span class="badge Accepted">Accepted</span></td>
</tr>
<?php } ?>
</table>
