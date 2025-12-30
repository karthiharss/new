<table>
<tr>
<th>#</th><th>Pet</th><th>Breed</th><th>Contact</th><th>Type</th><th>Status</th><th>Action</th>
</tr>

<?php $i=1;
$m=$conn->query("SELECT * FROM missing_pets WHERE status='Pending'");
while($r=$m->fetch_assoc()){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['pet_name'] ?></td>
<td><?= $r['breed'] ?></td>
<td><?= $r['contact'] ?></td>
<td>Missing</td>
<td><span class="badge Pending">Pending</span></td>
<td>
<a class="btn accept" href="update_missing_status.php?id=<?= $r['id'] ?>&status=Accepted">Accept</a>
<a class="btn reject" href="update_missing_status.php?id=<?= $r['id'] ?>&status=Rejected">Reject</a>
</td>
</tr>
<?php } ?>
</table>
