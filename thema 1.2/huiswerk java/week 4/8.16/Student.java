public class Student extends person
{
    private String id;
    private int credits;

    public Student (String studentID, String name, String adress, int phonenumber)
    {
        super ( name, adress, phonenumber );
        id = studentID;
        credits = 0;
    }

    public String getName()
    {
        return super.getName();
    }
    
    public void changeName(String replacementName)
    {
        name = replacementName;
    }

    public String getStudentID()
    {
        return id;
    }

    public void addCredits(int additionalPoints)
    {
        credits += additionalPoints;
    }

    public int getCredits()
    {
        return credits;
    }

    /**
     * Return the login name of this student. The login name is a combination
     * of the first four characters of the student's name and the first three
     * characters of the student's ID number.
     */
    public String getLoginName()
    {
        return name.substring(0,4) + id.substring(0,3);
    }
    
    /**
     * Print the student's name and ID number to the output terminal.
     */
    public void print()
    {
        System.out.println(name + ", student ID: " + id + ", credits: " + credits);
    }
}
