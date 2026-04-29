<?php
namespace App\Core;

class Calculator {
    /**
     * Berechnet einen String wie "0,39+0,25" oder "5*1,20"
     */
    public static function calculate(string $formula): float {
        // Komma durch Punkt ersetzen, Leerzeichen entfernen
        $formula = str_replace(',', '.', trim($formula));
        $formula = preg_replace('/[^0-9\+\-\*\/\.]/', '', $formula);

        if (empty($formula)) return 0.0;

        // Sehr einfacher Parser für Grundrechenarten
        // Für komplexe Formeln nutzen wir hier eine kontrollierte Berechnung
        try {
            // Da wir nur Zahlen und Operatoren zulassen, ist ein kontrolliertes return möglich
            // Wir nutzen hier eine mathematische Lösung ohne eval:
            $result = 0;
            if (strpos($formula, '+') !== false) {
                $parts = explode('+', $formula);
                foreach ($parts as $p) $result += (float)$p;
            } elseif (strpos($formula, '-') !== false) {
                $parts = explode('-', $formula);
                $result = (float)array_shift($parts);
                foreach ($parts as $p) $result -= (float)$p;
            } elseif (strpos($formula, '*') !== false) {
                $parts = explode('*', $formula);
                $result = 1;
                foreach ($parts as $p) $result *= (float)$p;
            } else {
                $result = (float)$formula;
            }
            return (float)$result;
        } catch (\Exception $e) {
            return 0.0;
        }
    }
}