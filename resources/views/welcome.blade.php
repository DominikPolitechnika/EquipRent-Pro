<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EquipRent Pro - Demo API</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f3f4f6; color: #111827; }
        .container { max-width: 960px; margin: 0 auto; padding: 40px 24px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .header { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .status { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 14px; }
        .btn { border: 0; background: #111827; color: #fff; border-radius: 6px; padding: 8px 12px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 12px 16px; border-bottom: 1px solid #f3f4f6; }
        th { background: #f9fafb; }
        code { background: #e5e7eb; padding: 2px 6px; border-radius: 4px; }
        .badge { padding: 3px 8px; border-radius: 999px; font-size: 12px; }
        .green { background: #dcfce7; color: #166534; }
        .yellow { background: #fef3c7; color: #92400e; }
        .red { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <main class="container">
        <h1>Przykladowy widok + przykladowe API</h1>
        <p>
            Dane sa pobierane z endpointu <code>/api/sample-equipment</code>.
        </p>

        <div class="card">
            <div class="header">
                <span><strong>Lista sprzetu</strong></span>
                <button
                    id="reloadButton"
                    type="button"
                    class="btn"
                >
                    Odswiez
                </button>
            </div>

            <div id="statusBox" class="status">
                Ladowanie danych...
            </div>

            <div>
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa</th>
                            <th>Kategoria</th>
                            <th>Cena / dzien</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="equipmentTableBody"></tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        const tableBody = document.getElementById('equipmentTableBody');
        const statusBox = document.getElementById('statusBox');
        const reloadButton = document.getElementById('reloadButton');

        function statusBadge(status) {
            if (status === 'dostepny') {
                return '<span class="badge green">Dostepny</span>';
            }
            if (status === 'wypozyczony') {
                return '<span class="badge yellow">Wypozyczony</span>';
            }
            return '<span class="badge red">Serwis</span>';
        }

        async function loadEquipment() {
            statusBox.textContent = 'Ladowanie danych...';
            tableBody.innerHTML = '';

            try {
                const response = await fetch('/api/sample-equipment');
                if (!response.ok) {
                    throw new Error(`Blad HTTP: ${response.status}`);
                }

                const payload = await response.json();
                const items = payload.data ?? [];

                if (!items.length) {
                    statusBox.textContent = 'Brak danych do wyswietlenia.';
                    return;
                }

                statusBox.textContent = `Pobrano ${items.length} rekordy. Waluta: ${payload.meta?.currency ?? 'PLN'}.`;

                tableBody.innerHTML = items.map((item) => `
                    <tr>
                        <td>${item.id}</td>
                        <td><strong>${item.name}</strong></td>
                        <td>${item.category}</td>
                        <td>${Number(item.daily_rate).toFixed(2)} PLN</td>
                        <td>${statusBadge(item.status)}</td>
                    </tr>
                `).join('');
            } catch (error) {
                statusBox.textContent = `Nie udalo sie pobrac danych: ${error.message}`;
            }
        }

        reloadButton.addEventListener('click', loadEquipment);
        loadEquipment();
    </script>
</body>
</html>