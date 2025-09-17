<?php

// JSON fájl elérési útja
$file = 'temak.json';

// Ha létezik a fájl, beolvassuk
if (file_exists($file)) {
    $jsonData = file_get_contents($file);
} else {
    // Ha nem létezik a fájl, alapértelmezett adatokat állítunk be
    $jsonData = '{
        "temak": [
            {
                "id": 1,
                "nev": "PHP alapok"
            }
        ]
    }';
}

// A JSON adatokat dekódoljuk PHP tömbbé
$temak = json_decode($jsonData, true);

// Új téma hozzáadása (ha POST kérést kapunk)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nev'])) {
        // Új téma hozzáadása
        $newTema = [
            "id" => count($temak['temak']) + 1, // Új ID
            "nev" => $_POST['nev']  // A POST-ban küldött adatok
        ];

        // Hozzáadjuk az új témát
        $temak['temak'][] = $newTema;

        // Frissítjük a JSON fájlt
        file_put_contents($file, json_encode($temak, JSON_PRETTY_PRINT));

        // Megjelenítjük a siker üzenetet
        echo "Új téma hozzáadva!<br><br>";
    }

    // Téma törlése (ha törlés kérés érkezik)
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        // Téma eltávolítása az ID alapján
        foreach ($temak['temak'] as $key => $tema) {
            if ($tema['id'] == $deleteId) {
                unset($temak['temak'][$key]);  // Eltávolítjuk a témát
                break;
            }
        }
        
        // Újrarendezés (a törlés után)
        $temak['temak'] = array_values($temak['temak']); 

        // Frissítjük a JSON fájlt
        file_put_contents($file, json_encode($temak, JSON_PRETTY_PRINT));

        echo "Téma törölve!<br><br>";
    }
}

// Kiíratjuk a témákat
foreach ($temak['temak'] as $tema) {
    echo "Téma ID: " . $tema['id'] . "<br>";
    echo "Téma neve: " . $tema['nev'] . "<br>";

    // Törlés gomb minden téma mellett
    echo '<form method="POST" style="display:inline;">
        <input type="hidden" name="delete_id" value="' . $tema['id'] . '">
        <button type="submit">Téma törlése</button>
    </form>';
    echo "<br><br>";
}

?>

<!-- HTML form a téma hozzáadásához -->
<form method="POST">
    <label for="nev">Téma neve:</label>
    <input type="text" name="nev" id="nev" required><br>
    <button type="submit">Téma hozzáadása</button>
</form>
