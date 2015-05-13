

<div class="container">
    <div class="page-header">{{ pageHeader }}</div>

    <table class="table-list">
        <tr>
            <td width="150px">{{ label.direct("Label-Full-Name") }}</td>
            <th>{{ user.full_name }}</th>
        </tr>
        <tr>
            <td>{{ label.direct("Label-Email") }}</td>
            <th>{{ user.email }}</th>
        </tr>
        <tr>
            <td>{{ label.direct("Label-Phone-Number") }}</td>
            <th>{{ user.phone_number }}</th>
        </tr>
    </table>
</div>