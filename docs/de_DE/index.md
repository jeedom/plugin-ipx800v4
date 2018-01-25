Ce plugin permet de gérer un IPX 800 v4

Configuration du plugin 
=======================

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci,
il n’y a aucune configuration à ce niveau.

> **Note**
>
> Vous pouvez voir sur cette page l’état du démon qui surveille l’IPX800

Configuration des équipements 
=============================

La configuration des équipements IPX800 est accessible à partir du menu
plugin puis protocole domotique :

Hier finden sie die ganze Konfiguration Ihrer Geräte:

-   **Nom de l’équipement IPX800** : nom de votre équipement IPX800,

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement,

-   **Activer** : permet de rendre votre équipement actif,

-   **Visible** : rend votre équipement visible sur le dashboard,

-   **Catégorie** : les catégories de l’équipement (il peut appartenir à
    plusieurs catégories),

-   **IP** : L’ip de l’IPX800,

-   **Clef API** : clef api de l’IPX800 (par default la clef API
    est apikey)

-   **Extension** : il faut ici indiquer les extensions présente sur
    l’ipx800 pour que jeedom puisse les interroger. Exemple pour enOcean
    il faut mettre ENO (si vous en avez plusieurs il faut les séparer
    par des ,). Toutes les extensions sont normalement supportée, pour
    le code de l’extension à mettre il faut se reporter à la
    documentation de celle-ci.

Le plugin par defaut ne creer rien, ca sera à vous de le faire en
fonction de votre configuration, mais vous serez guider.

Commande 
========

Action 
------

Vous avez 3 types d’action :

-   **On** : permet de mettre à 1 une sortie (ou entrée virtuelle), ou
    d’affecter une valeur à une entrée analogique (ou compteur)

-   **Off** : permet de mettre à 0 une sortie (ou entrée virtuelle)

-   **Bascule** : permet d’inverser l’état d’une sortie (ou
    entrée virtuelle)

Vous avez 5 types d’actionneurs :

-   **Relais**

-   **Sortie virtuelle**

-   **Entrée virtuelle**

-   **Entrée analogique virtuelle**

-   **Compteur**

> **Note**
>
> Certain type d’actionneur peuvent etre masqué en fonction du type
> d’action

Ensuite en fonction du type d’action et de l’actionneur vous avez
plusieurs paramètres qui peuvent être :

-   numéro du relai

-   numéro de la sortie virtuelle

-   numéro de l’entrée virtuelle

-   numéro de l’entrée analogique et valeur à affecter (laisser vide si
    vous voulez choisir avec le curseul)

-   numéro du compteur et opération (ex +200 ou -100)

Info 
----

Vous avez 11 types differents :

-   Relais

-   Entrée digital

-   Entrée virtuelle

-   Sortie virtuelle

-   Watchdog

-   EnOcean

-   Entrée analogique

-   Entrée analogique virtuelle

-   Compteur

-   Volet roulant : option sous la forme 1-3 pour le volet 3 de
    l’extension VR 1

-   THL : sous la forme 1-TEMP pour la température du capteur 1, 3-HUM
    pour l’humidité du capteur 3 ou 2-LUM pour la luminosité du capteur
    2

Pour chaque type jeedom vous demandera le numéro de l’information voulue

Template 
========

Pour vous aider il y a un template qui permet de creer certain type de
commande d’un seul coup et plus rapidement.

Changelog 
=========

-   Amélioration du support enOcean

-   Correction de bugs sur la récuperération des informations des
    extensions

-   Ajout de l’extension VR

-   Optimisation de la synchronisation avec l’ipx800

-   Support des modules THL

-   Ajout d’un moteur de template pour la génération des commandes


