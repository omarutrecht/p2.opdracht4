<?php

class InstructeurModel
{
    private $db;

    public function __construct()
    {
        // Initialize the Database class
        $this->db = new Database();
    }

    public function getInstructeurs()
    {
        // SQL query to retrieve instructeur information
        $sql = "SELECT Id
                      ,Voornaam
                      ,Tussenvoegsel
                      ,Achternaam
                      ,Mobiel
                      ,DatumInDienst
                      ,AantalSterren
                      ,IsActief
                FROM  Instructeur
                ORDER BY AantalSterren DESC";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    function deleteInstructeur($instructeurId)
    {
        $sql = "delete from VoertuigInstructeur where InstructeurId = ?";
        $this->db->query($sql);
        $this->db->bind(1, $instructeurId);
        $this->db->single();
    
        $sql = "delete from Instructeur where Id = ?";
        $this->db->query($sql);
        $this->db->bind(1, $instructeurId);
        $this->db->single();
    }

    public function getTypeVoertuigen()
    {
        $sql = "SELECT Id
                      ,TypeVoertuig
                FROM  TypeVoertuig";

        $this->db->query($sql);
        return $this->db->resultSet();
    }


    public function getBeschikbareVoertuigen()
    {
        $sql = "SELECT       VOER.Type
                            ,VOER.Kenteken
                            ,VOER.Bouwjaar
                            ,VOER.Brandstof
                            ,TYVO.TypeVoertuig
                            ,TYVO.RijbewijsCategorie
                            ,VOER.Id

                FROM        Voertuig    AS  VOER
                
                INNER JOIN  TypeVoertuig AS TYVO

                ON          TYVO.Id = VOER.TypeVoertuigId
                
                LEFT JOIN  VoertuigInstructeur AS VOIN
                
                ON          VOIN.VoertuigId = VOER.Id

                LEFT JOIN  Instructeur AS INST

                ON          VOIN.InstructeurId = INST.Id

                WHERE       VOIN.InstructeurId IS NULL

                OR         VOIN.IsActief = 0
                
                ORDER BY    VOER.Bouwjaar DESC";

        $this->db->query($sql);
        return $this->db->resultSet();
    }


    public function getToegewezenVoertuigen($Id)
    {
        $sql = "SELECT       VOER.Type
                            ,VOER.Kenteken
                            ,VOER.Bouwjaar
                            ,VOER.Brandstof
                            ,TYVO.TypeVoertuig
                            ,TYVO.RijbewijsCategorie
                            ,VOER.Id
                            ,(SELECT COUNT(*) >= 2 from VoertuigInstructeur where VoertuigId = VOER.Id) as Multiple

                FROM        Voertuig    AS  VOER
                
                INNER JOIN  TypeVoertuig AS TYVO

                ON          TYVO.Id = VOER.TypeVoertuigId
                
                INNER JOIN  VoertuigInstructeur AS VOIN
                
                ON          VOIN.VoertuigId = VOER.Id
                
                INNER JOIN  Instructeur AS INST

                ON          VOIN.InstructeurId = INST.Id

                WHERE       INST.IsActief AND VOIN.InstructeurId = $Id

                ORDER BY    TYVO.RijbewijsCategorie DESC";

        $this->db->query($sql);
        return $this->db->resultSet();
    }
   
}
