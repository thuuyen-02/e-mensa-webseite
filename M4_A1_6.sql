USE emensawerbeseite;

SELECT * FROM Wunschgericht ORDER BY Erstellungsdatum DESC LIMIT 5;

SELECT Ersteller.Name, COUNT(Wunschgericht.Wunschgericht_ID) AS Anzahl_Wünsche
FROM Ersteller
         LEFT JOIN Wunschgericht ON Ersteller.Ersteller_ID = Wunschgericht.Ersteller_ID
GROUP BY Ersteller.Name;
