using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;

namespace isola
{

	public partial class MainForm : Form
	{
		private int currentPlayer = 0;
		private int mode = 0; //0 - move, 1 - mark mode

		private int[][] playerPosition;
		private int[][] data;
		
		private System.Windows.Forms.Button[][] buttons;
		
		private bool finished(){
			return true;
			//check player 1 fields near
			//check player 2 fields near
			
		}
		private bool canMoveHere(int x, int y) {
			if (x > this.playerPosition[currentPlayer-1][0]+1
			   || y > this.playerPosition[currentPlayer-1][1]+1
			   || x < this.playerPosition[currentPlayer-1][0]-1
			   || y < this.playerPosition[currentPlayer-1][1]-1
			   || (x == this.playerPosition[currentPlayer-1][0]
			       && y == this.playerPosition[currentPlayer-1][1])
			  ) return false;
			if (this.data[x][y] == -1){
				return false;
			}
			return true;
		}

		private bool canMarkHere(int x, int y) {
			return this.data[x][y] == 0;
		}

		private void move(int x, int y) {
			if (this.canMoveHere(x,y)){
				//new player button
				this.buttons[x][y].Text = "P" + this.currentPlayer.ToString();
				this.data[x][y] = this.currentPlayer;
				
				//old player button
				this.buttons[this.playerPosition[currentPlayer-1][0]]
							 [this.playerPosition[currentPlayer-1][1]].Text = "";
				this.data[this.playerPosition[currentPlayer-1][0]]
							 [this.playerPosition[currentPlayer-1][1]] =0;
				
				//update player position info
				this.playerPosition[currentPlayer-1][0] = x;
				this.playerPosition[currentPlayer-1][1] = y;
				
				//change mode
				this.mode = 1;
				this.toolStripStatusLabel1.Text = "Wyłącz pole.";
			}
		}
		private void mark(int x, int y) {
			if (this.canMarkHere(x,y)){
				this.toolStripStatusLabel1.Text ="Przesuń pionek.";
				this.data[x][y] = -1;
				this.buttons[x][y].BackColor = Color.DarkSlateGray;
				this.currentPlayer = currentPlayer==1 ? 2 : 1; //switch player
				this.mode = 0; //change mode
				
				this.toolStripStatusLabel1.Text = "Gracz " + currentPlayer.ToString();
			}
		}

		public MainForm()
		{

			InitializeComponent();
			data = new int[10][];
			currentPlayer=1;
			this.playerPosition = new int[2][];
			
			//player1
			this.playerPosition[0] = new int[2];
			this.playerPosition[0][0] = 0; //x
			this.playerPosition[0][1] = 0; //y
			//player2
			this.playerPosition[1] = new int[2];
			this.playerPosition[1][0] = 9; //x
			this.playerPosition[1][1] = 9; //y
				

			this.toolStripStatusLabel1.Text = "Gracz " + currentPlayer.ToString();
			buttons = new System.Windows.Forms.Button[10][];
			
			//1 - player 1, 2- plaer 2, 0 - empty, -1 - blocked
			
			for (int i = 0; i < 10; ++i){
				data[i] = new int[10];
				for (int j = 0; j < 10; ++j){
					data[i][j] = 0; //default state
				}
			}
			
			data[0][0] = 1; //player 1
			data[9][9] = 2; //player 2
			
			for (int i = 0; i <10; ++i){
				buttons[i] = new System.Windows.Forms.Button[10];
				
				for (int j = 0; j < 10; ++j){
					buttons[i][j] = new System.Windows.Forms.Button();
					
					buttons[i][j].Size = new Size(40,40);
					buttons[i][j].Padding = new System.Windows.Forms.Padding(0);
					buttons[i][j].Margin = new System.Windows.Forms.Padding(0);
					buttons[i][j].FlatStyle = FlatStyle.Popup;
					buttons[i][j].Name = i.ToString()+"-"+j.ToString();
					buttons[i][j].BackColor = Color.LightGray;

					if (i >= 3 && j >=3 &&i <=6&&j<=6 ) {
						buttons[i][j].Enabled = false;
						
						buttons[i][j].BackColor = Color.DarkSlateGray;
					}

					buttons[i][j].MouseHover += 
						 new System.EventHandler(this.onButtonHover);
					this.flowLayoutPanel1.Controls.Add(buttons[i][j]);
					
					buttons[i][j].Click +=new System.EventHandler(this.onButtonClick);
					
				}
			}
			buttons[0][0].Text = "P1";
			buttons[9][9].Text = "P2";

		}
		
		void onButtonHover(object sender, EventArgs e)
		{
			int[] cords = this.cordsFromName(sender);
			
			if (this.mode == 0 ) {
				if (this.canMoveHere(cords[0],cords[1]) && this.data[cords[0]][cords[1]] != -1){
					((System.Windows.Forms.Button)sender).BackColor = System.Drawing.Color.Green;
				}else if(this.data[cords[0]][cords[1]] != -1){
					((System.Windows.Forms.Button)sender).BackColor = System.Drawing.Color.Orange;
				}
				
			} else {
				if (this.canMarkHere(cords[0], cords[1]) && this.data[cords[0]][cords[1]] != -1){
					((System.Windows.Forms.Button)sender).BackColor = Color.Black;	
					
				}
			}
			((System.Windows.Forms.Button)sender).MouseLeave += new System.EventHandler(this.onButtonLeave);
				
		}

		void onButtonLeave(object sender, EventArgs e)
		{
			int[] cords = this.cordsFromName(sender);
			if (this.data[cords[0]][cords[1]] != -1){
				((System.Windows.Forms.Button)sender).BackColor = Color.LightGray;
			}
		}
		
		int[] cordsFromName(object sender) {
			string tmp = ((System.Windows.Forms.Button)sender).Name;
			string[] cords = tmp.Split('-');
			
			return new int[]{Convert.ToInt32(cords[0]), Convert.ToInt32(cords[1])};
		}
		
		void onButtonClick(object sender, EventArgs e)
		{
			int[] cords  = this.cordsFromName(sender);
			if (this.mode==0){
				this.move(cords[0], cords[1]);
			}else{
				this.mark(cords[0], cords[1]);
			}
			
		}
	}
}
