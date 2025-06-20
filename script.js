const express = require("express");
const mysql = require("mysql");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "", // Change if needed
    database: "happy_life",
});

db.connect(err => {
    if (err) throw err;
    console.log("Connected to MySQL database");
});

app.post("/save_contact", (req, res) => {
    const { name, phone } = req.body;

    if (!name || !phone) {
        return res.status(400).json({ status: "error", message: "All fields are required" });
    }

    const sql = "INSERT INTO contacts (name, phone) VALUES (?, ?)";
    db.query(sql, [name, phone], (err, result) => {
        if (err) {
            res.status(500).json({ status: "error", message: "Failed to save contact" });
        } else {
            res.json({ status: "success", message: "Contact saved successfully!" });
        }
    });
});

app.listen(3000, () => {
    console.log("Server running on port 3000");
});
