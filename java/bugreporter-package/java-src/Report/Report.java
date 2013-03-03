package Report;

import Reporter.Reporter; // We need to import Reporter package

public class Report {
    public static void main(String[] args) throws Exception {
        final Reporter report = new Reporter("http://localhost/ws/submit"); // reporter must be final
        report.setKey("54rGRDG!#@$");

        Tester.divide(8, 0);
    }
}
