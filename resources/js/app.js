import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

import "./bootstrap";

window.Chart = Chart;
