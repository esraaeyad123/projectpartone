@extends('layouts.app')
@section('title', 'Report Details - AAM LIMS')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
@media print {
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .report-sheet {
        box-shadow: none !important;
        border: none !important;
    }
    .action-button, .report-actions {
        display: none !important;
    }
}

.report-sheet {
    background: white;
    color: black;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.report-image-header,
.report-image-footer {
    width: 100%;
    height: auto;
}

.test-results-table th {
    background-color: #f2f2f2;
}
</style>

@endsection

@section('content')
<div class="container">
    <main class="main-content">
        <section class="section-content active">
            <h2><i class="fa-solid fa-file-invoice"></i> Report Details</h2>
        </section>

        <div class="report-actions">
            <button class="action-button button-gray" onclick="goBack()">
                <i class="fa-solid fa-arrow-left"></i> Go Back to Reports
            </button>
            <button class="action-button button-gray" onclick="saveReport()">
                <i class="fa-solid fa-file-pdf"></i> Save As PDF
            </button>
            <button class="action-button button-gray" onclick="printReport()">
                <i class="fa-solid fa-print"></i> Print
            </button>
            <button class="action-button button-gray" onclick="submitReport()">
                <i class="fa-solid fa-paper-plane"></i> Submit for Approval
            </button>
            <button class="action-button button-gray" onclick="checkReport()">
                <i class="fa-solid fa-square-check"></i> Check Report
            </button>
            <button class="action-button button-approve" onclick="approveReport()">
                <i class="fa-solid fa-circle-check"></i> Approve Report
            </button>
            <button class="action-button button-decline" onclick="declineReport()">
                <i class="fa-solid fa-circle-xmark"></i> Decline Report
            </button>
            <button class="action-button button-remove" onclick="removeReport()">
                <i class="fa-solid fa-trash-can"></i> Remove Report
            </button>
            <button class="action-button button-pass" onclick="passReport()">
                <i class="fa-solid fa-check"></i> Pass
            </button>
            <button class="action-button button-fail" onclick="failReport()">
                <i class="fa-solid fa-times"></i> Fail
            </button>
        </div>

        <section id="report-details-section">
            <div class="report-sheet">
                <img src="{{ asset('images/header.png') }}" alt="AAM Header" class="report-image-header" />

                <h2 class="report-title">Field Density Test Report on Soil and Soil Aggregate By Nuclear Method</h2>

                <div class="report-details-grid">
                    <div class="details-item"><span class="label">ORDER #</span><span class="value" id="orderNumber">AAM-NG-O-25-07757</span></div>
                    <div class="details-item"><span class="label">REPORT #</span><span class="value" id="reportNumber">AAM-NG-R-25-07757</span></div>
                    <div class="details-item"><span class="label">PROJECT</span><span class="value" id="projectName">إستكمال شبكات الصرف الصحي لمدينتي الهفوف والمبرز المرحلة الأولى</span></div>
                    <div class="details-item"><span class="label">REPORT DATE</span><span class="value" id="reportDate">August 24, 2025 12:00 PM</span></div>
                    <div class="details-item"><span class="label">AML CLIENT</span><span class="value" id="clientName">شركة اعمار السعودية للإنشاء والتطوير</span></div>
                    <div class="details-item"><span class="label">PAGES</span><span class="value" id="pages">1/1</span></div>
                    <div class="details-item"><span class="label">CONSULTANT</span><span class="value" id="consultant">N.P</span></div>
                    <div class="details-item"><span class="label">SUBCONTRACTOR</span><span class="value" id="subcontractor">شركة اعمار السعودية للإنشاء والتطوير</span></div>
                    <div class="details-item"><span class="label">SAMPLE #</span><span class="value" id="sampleNumber">AAM-NG-S-25-07757</span></div>
                    <div class="details-item"><span class="label">CONTACT</span><span class="value" id="contact">Name: Mob.: Email:</span></div>
                    <div class="details-item"><span class="label">PROJECT #</span><span class="value" id="projectCode">N.P.</span></div>
                    <div class="details-item"><span class="label">REPORTED BY</span><span class="value" id="reportedBy">H.Abdelmuti</span></div>
                    <div class="details-item"><span class="label">TEST LOCATION/STR.</span><span class="value" id="testLocation">LINE 2-1-1</span></div>
                    <div class="details-item"><span class="label">SAMPLE SOURCE</span><span class="value" id="sampleSource">Site</span></div>
                    <div class="details-item"><span class="label">LOCATED BY</span><span class="value" id="locatedBy">CONSULTANT'S REP</span></div>
                    <div class="details-item"><span class="label">TEST METHOD</span><span class="value" id="testMethod">ASTM D6938</span></div>
                    <div class="details-item"><span class="label">DATE TESTED</span><span class="value" id="dateTested">23-Aug-25</span></div>
                    <div class="details-item"><span class="label">TESTED BY</span><span class="value" id="testedBy">MOHAMMED ALLAM</span></div>
                    <div class="details-item"><span class="label">RFU/MR</span><span class="value" id="rfuMr">861015237877</span></div>
                    <div class="details-item"><span class="label">TEST SPOTS</span><span class="value" id="testSpots">3</span></div>
                    <div class="details-item"><span class="label">REQ. D.O.C(%)</span><span class="value" id="reqDoc">95</span></div>
                    <div class="details-item"><span class="label">WEATHER</span><span class="value" id="weather">SUNNY</span></div>
                    <div class="details-item"><span class="label">MMC (%)</span><span class="value" id="mmc">10.0</span></div>
                    <div class="details-item"><span class="label">MDD (kg/m³)</span><span class="value" id="mdd">2.052</span></div>
                    <div class="details-item"><span class="label">DEVICE MANUFACT</span><span class="value" id="deviceManufact"></span></div>
                    <div class="details-item"><span class="label">DEVICE MODEL</span><span class="value" id="deviceModel">Nil</span></div>
                    <div class="details-item"><span class="label">SERIAL</span><span class="value" id="serial"></span></div>
                    <div class="details-item"><span class="label">NEXT CALIB. DATE</span><span class="value" id="nextCalibDate"></span></div>
                </div>

                <table class="test-results-table">
                    <thead>
                        <tr>
                            <th>TEST #</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LOCATION</td>
                            <td>MH2 to MH3</td>
                            <td>MH2 to MH1</td>
                            <td>MH1 to MH1</td>
                        </tr>
                        <tr>
                            <td>MATERIAL</td>
                            <td>Base Course</td>
                            <td>Base Course</td>
                            <td>Base Course</td>
                        </tr>
                        <tr>
                            <td>LAYER</td>
                            <td>Last</td>
                            <td>Last</td>
                            <td>Last</td>
                        </tr>
                        <tr>
                            <td>TEST DEPTH(mm)</td>
                            <td>150</td>
                            <td>150</td>
                            <td>150</td>
                        </tr>
                        <tr>
                            <td>WET DENSITY (kg/m³)</td>
                            <td>2.197</td>
                            <td>2.052</td>
                            <td>2.062</td>
                        </tr>
                        <tr>
                            <td>MOISTURE CONTENT (%)</td>
                            <td>6.3</td>
                            <td>4.1</td>
                            <td>3.2</td>
                        </tr>
                        <tr>
                            <td>DRY DENSITY (kg/m³)</td>
                            <td>2.067</td>
                            <td>1.971</td>
                            <td>1.942</td>
                        </tr>
                        <tr>
                            <td>DEGREE OF COMPACTION (%)</td>
                            <td>100.7</td>
                            <td>96.1</td>
                            <td>97.4</td>
                        </tr>
                        <tr>
                            <td>PASS/FAIL</td>
                            <td class="pass-fail-status" style="color: #27ae60;">PASS</td>
                            <td class="pass-fail-status" style="color: #27ae60;">PASS</td>
                            <td class="pass-fail-status" style="color: #27ae60;">PASS</td>
                        </tr>
                        <tr>
                            <td>TEST/RETEST</td>
                            <td>TEST</td>
                            <td>TEST</td>
                            <td>TEST</td>
                        </tr>
                    </tbody>
                </table>

                <div class="comments-section">
                    <h4>COMMENTS ON TEST RESULTS:</h4>
                    <p>This is a placeholder for the comments on the test results. You can add the dynamic content here.</p>
                </div>

                <div class="comments-section decline-comments-section" style="display: none;">
                    <h4>Decline Reason:</h4>
                    <textarea id="declineComment" placeholder="Enter reason for decline..."></textarea>
                    <button class="action-button button-decline" onclick="submitDecline()">
                        <i class="fa-solid fa-paper-plane"></i> Submit Decline
                    </button>
                </div>

                <div class="comments-section">
                    <h4>Notes:</h4>
                    <ul class="notes-list">
                        <li>1. The test results represent the submitted sample(s) only.</li>
                        <li>2. This report shall not be reproduced except in full, without written approval of AAM LAB.</li>
                    </ul>
                </div>

                <p class="report-info">
                    *** This document is electronically generated and digitally signed ***
                </p>

                <img src="{{ asset('images/footer.png') }}" alt="AAM Footer" class="report-image-footer" />
            </div>
        </section>
    </main>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/report-details.js') }}" type="text/javascript"></script>

<script>
function goBack() {
    window.history.back();
}
</script>
@endsection
