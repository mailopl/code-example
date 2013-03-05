using System.Drawing;
using System.Windows.Forms;
using System.Diagnostics;

namespace Toodie
{
    /// <summary>
    /// Summary description for AboutBoxLinks.
    /// </summary>
    public class AboutBoxLinks : System.Windows.Forms.Form
    {
        internal System.Windows.Forms.LinkLabel lnkWebSite;
        private RichTextBox richTextBox1;
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.Container components = null;
        public System.Resources.ResourceManager rm;
        
        public void LocalizeUI()
        {
            this.lnkWebSite.Text = rm.GetString("changes");
            this.Text = rm.GetString("about");

        }
        public AboutBoxLinks()
        {
            //
            // Required for Windows Form Designer support
            //
            InitializeComponent();
        }

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                if (components != null)
                {
                    components.Dispose();
                }
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code
        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(AboutBoxLinks));
            this.lnkWebSite = new System.Windows.Forms.LinkLabel();
            this.richTextBox1 = new System.Windows.Forms.RichTextBox();
            this.SuspendLayout();
            // 
            // lnkWebSite
            // 
            this.lnkWebSite.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.lnkWebSite.LinkArea = new System.Windows.Forms.LinkArea(0, 0);
            this.lnkWebSite.Location = new System.Drawing.Point(2, 5);
            this.lnkWebSite.Name = "lnkWebSite";
            this.lnkWebSite.Size = new System.Drawing.Size(282, 84);
            this.lnkWebSite.TabIndex = 4;
            this.lnkWebSite.Text = resources.GetString("lnkWebSite.Text");
            this.lnkWebSite.LinkClicked += new System.Windows.Forms.LinkLabelLinkClickedEventHandler(this.lnkWebSite_LinkClicked);
            // 
            // richTextBox1
            // 
            this.richTextBox1.Location = new System.Drawing.Point(5, 90);
            this.richTextBox1.Name = "richTextBox1";
            this.richTextBox1.Size = new System.Drawing.Size(268, 130);
            this.richTextBox1.TabIndex = 7;
            this.richTextBox1.Text = "Version changes:\n\n--> Version: 1.0.00\nFirst public release.\n";
            // 
            // AboutBoxLinks
            // 
            this.AutoScaleBaseSize = new System.Drawing.Size(5, 14);
            this.ClientSize = new System.Drawing.Size(280, 225);
            this.Controls.Add(this.richTextBox1);
            this.Controls.Add(this.lnkWebSite);
            this.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow;
            this.Name = "AboutBoxLinks";
            this.ShowIcon = false;
            this.Text = "About Toodie";
            this.Load += new System.EventHandler(this.AboutBoxLinks_Load);
            this.ResumeLayout(false);

        }
        #endregion

        /// <summary>
        /// The main entry point for the application.
        /// </summary>


        private void AboutBoxLinks_Load(object sender, System.EventArgs e)
        {
        
            //lnkWebSite.Links.Add(4, 17, "http://odd.com.pl/#3");
        }

        private void lnkWebSite_LinkClicked(object sender, System.Windows.Forms.LinkLabelLinkClickedEventArgs e)
        {
            e.Link.Visited = true;
            System.Diagnostics.Process.Start((string)e.Link.LinkData);

        }
    }
}
