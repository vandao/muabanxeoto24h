

<div class="container">
    <div class="page-header"><?php echo $pageHeader; ?></div>

    <table class="table-list">
        <tr>
            <td width="150px"><?php echo $this->label->direct('Label-Full-Name'); ?></td>
            <th><?php echo $user->full_name; ?></th>
        </tr>
        <tr>
            <td><?php echo $this->label->direct('Label-Email'); ?></td>
            <th><?php echo $user->email; ?></th>
        </tr>
        <tr>
            <td><?php echo $this->label->direct('Label-Phone-Number'); ?></td>
            <th><?php echo $user->phone_number; ?></th>
        </tr>
    </table>
</div>