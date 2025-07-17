<style>
    /* * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f2f5;
    } */

    .search-container {
        padding: 20px;
        text-align: center;
        background-color: #4a69bd;
    }

    #searchInput {
        width: 300px;
        padding: 10px 15px;
        font-size: 16px;
        border: none;
        border-radius: 25px;
        outline: none;
        transition: box-shadow 0.3s ease;
    }

    #searchInput:focus {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    table {
        width: 80%;
        margin: 30px auto;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 10px;
        background-color: #fff;
    }

    thead {
        background-color: #1e3799;
        color: #fff;
    }

    thead th {
        padding: 15px;
        cursor: pointer;
        position: relative;
        user-select: none;
        text-align: -webkit-center;
    }

    thead th::after {
        content: '';
        position: absolute;
        right: 20px;
        border: 6px solid transparent;
        border-top-color: #fff;
        transform: translateY(-50%);
        top: 50%;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    thead th:hover::after {
        opacity: 1;
    }

    tbody tr {
        transition: background-color 0.3s ease;
    }

    tbody tr:nth-child(even) {
        background-color: #f1f2f6;
    }

    tbody tr:hover {
        background-color: #dcdde1;
    }

    td {
        padding: 15px;
        text-align: center;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    tbody tr {
        animation: fadeIn 0.5s ease-in;
    }
</style>

<?php
$events = $conn->query("SELECT 
                            res.result_id, 
                            mi.title,
                            mi.image_type,
                            res.diagnosis, 
                            res.confidence, 
                            res.created_at, 
                            mi.image_id,
                            mi.user_id,
                            mi.delete_flag,
                            mi.status,
                            res.image_id, 
                            res.status, 
                            res.delete_flag 
                            FROM `medicalimages` mi
                            inner join
                            `diagnosticresults` res on mi.image_id = res.image_id
                            where
                            res.status = 'Reviewed'
                            and
                            mi.status = 1
                            and 
                            res.delete_flag = 0
                            and
                            mi.delete_flag = 0
                            and 
                            user_id = '{$_settings->userdata('user_id')}'
                            order by abs(unix_timestamp(res.created_at)) asc");

$eventsArray = [];
while ($row = $events->fetch_assoc()) {
    $eventsArray[] = $row;
}
?>

<section class="py-3">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search events...">
    </div>

    <table id="dataTable">
        <thead>
            <tr>
                <th data-column="result_id" data-order="desc">Result ID</th>
                <th data-column="title" data-order="desc">Image Title</th>
                <th data-column="image_type" data-order="desc">Image Type</th>
                <th data-column="diagnosis" data-order="desc">Diagnosis</th>
                <th data-column="confidence" data-order="desc">Confidence</th>
                <th data-column="created_at" data-order="desc">created_at</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventsArray as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['result_id']) ?></td>
                    <td><?= htmlspecialchars($event['title']) ?></td>
                    <td><?= htmlspecialchars($event['image_type']) ?></td>
                    <td><?= htmlspecialchars($event['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($event['confidence']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($event['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script>
    const events = <?= json_encode($eventsArray) ?>;

    function renderTable(data) {
        const tableBody = document.querySelector('#dataTable tbody');
        tableBody.innerHTML = '';

        data.forEach(event => {
            const row = document.createElement('tr');
            for (let key in event) {
                const cell = document.createElement('td');
                if (key != "status" && key != "image_id" && key != "user_id" && key != "delete_flag") {
                    cell.textContent = event[key];
                    row.appendChild(cell);
                }

            }
            tableBody.appendChild(row);
        });
    }

    renderTable(events);

    document.getElementById('searchInput').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const filteredEvents = events.filter(event =>
            Object.values(event).some(val => String(val).toLowerCase().includes(query))
        );
        renderTable(filteredEvents);
    });

    function handleSort(event) {
        const header = event.target;
        const column = header.getAttribute('data-column');
        const order = header.getAttribute('data-order');
        const newOrder = order === 'desc' ? 'asc' : 'desc';
        header.setAttribute('data-order', newOrder);

        const sortedEvents = [...events].sort((a, b) => {
            if (a[column] > b[column]) {
                return newOrder === 'asc' ? 1 : -1;
            }
            if (a[column] < b[column]) {
                return newOrder === 'asc' ? -1 : 1;
            }
            return 0;
        });

        renderTable(sortedEvents);
    }

    document.querySelectorAll('th').forEach(header => header.addEventListener('click', handleSort));
</script>