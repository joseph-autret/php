<?php include 'header.php'; ?>

<main id="adminpanel">
    <h2>Dashboard admin</h2>


    <table>
        <thead>
            <tr>
            <th>
                <button id="showLogs">Logs</button>
            </th>
            <th>
                <button id="showArticles">Articles en attente de validation</button>
            </th>
            </tr>
        </thead>

        <tbody id="logs" style="display:none;">
            <tr>
                <th>Id_activity</th>
                <th>Activity_type</th>
                <th>Activity_timestamp</th>
                <th>User_id</th>
            </tr>
            <?php if (isset($activities) && !empty($activities)): ?>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($activity['activity_id']); ?></td>
                        <td><?php echo htmlspecialchars($activity['activity_type']); ?></td>
                        <td><?php echo htmlspecialchars($activity['activity_timestamp']); ?></td>
                        <td><?php echo htmlspecialchars($activity['user_id']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucune activité trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <tbody id="articles" style="display:none;">
            <tr>
                <td>Nom_article</td>
                <td>Auteur</td>
                <td>Date de création</td>
                <td>Tags</td>
            </tr>
        </tbody>
    </table>
</main>

<script>
    document.getElementById('showLogs').addEventListener('click', function() {
        document.getElementById('logs').style.display = 'table-row-group'; 
        document.getElementById('articles').style.display = 'none'; 
    });

    document.getElementById('showArticles').addEventListener('click', function() {
        document.getElementById('logs').style.display = 'none';
        document.getElementById('articles').style.display = 'table-row-group';
    });
</script>

<?php include 'footer.php'; ?>
