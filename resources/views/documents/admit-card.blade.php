<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exam Name - Ac Year</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: auto;
            /* width: 29.7cm; */
            /* padding: 1cm; */
        }

        .a4-container {
            /* border: 2px solid black; */
            /* padding: 15px; */
            margin: auto;
        }

        table {
            width: 100%;
            border: 1px solid black;
        }

        table tr td {
            padding: 1rem;
        }

        table tr td:nth-child(2) {
            text-align: center;
        }

        table tr td:nth-child(3) {
            text-align: right;
        }

        table.routine-table {
            border-collapse: collapse;
        }

        table.routine-table .heading th, table.routine-table .data td {
            text-align: left;
            border: 1px solid black;
            padding: 5px;
        }

        table.routine-table .heading th {
            width: 15%;
        }

        table.routine-table .heading th:nth-child(1),
        table.routine-table .heading th:nth-child(5) {
            width: 5%;
        }

    </style>
</head>

<body>
    <div class="a4-container">
        <table>
            <tr>
                <td style="width:40%">
                    <span style="font-size: 1.5rem">School Name</span> <br>
                    Full Address of the school <br>
                    Email and Phone No
                </td>
                <td style="width:20%">
                    <img src="{{ $variables['LOGO'] }}" style="width: 100%; max-width: 150px" />
                </td>
                <td style="width:40%">
                    <span style="font-size: 1.5rem">Admit Card</span><br>
                    Exam Name (Provisional)<br>
                    Accademic Year
                </td>
            </tr>
        </table>
        <table style="border: none; margin-top:2rem;">
            <tr>
                <td style="width: 75%; padding: 0;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">Name:</span> Full Name of the Student
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">Class:</span> Class And Section
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">User ID:</span> Stuents User ID
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">Roll No:</span> Roll No
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">Admission No:</span> The Admission No
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">D.O.B</span> Students DOB
                            </td>table.routine-table .heading th 
                        </tr>
                    </table>
                </td>
                <td style="width: 25% padding: 0;  text-align: right;">
                    <img src="{{ $variables['LOGO'] }}" style="width: 100%; max-width: 150px; border: 1px solid black"/>
                </td>
            </tr>
        </table>

        <table style="margin-top: 2rem" class="routine-table">
            <tr>
                <th colspan="8">Exam Routine</th>
            </tr>
            <tr class="heading">
                <th>Sl</th>
                <th>Date</th>
                <th>Time</th>
                <th>Subject</th>

                <th>Sl</th>
                <th>Date</th>
                <th>Time</th>
                <th>Subject</th>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
            <tr class="data">
                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>

                <td>Sl</td>
                <td>Date</td>
                <td>Time</td>
                <td>Subject</td>
            </tr>
        </table>
    </div>
</body>

</html>
